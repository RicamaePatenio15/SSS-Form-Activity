<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "patenio_form");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$record_id = isset($_POST['record_id']) && !empty($_POST['record_id']) ? intval($_POST['record_id']) : null;
$is_update = ($record_id !== null);

$conn->begin_transaction();

$sql = "INSERT INTO tbl_record (
    last_name, first_name, middle_name, suffix,
    gender, marital_status, birthdate, nationality,
    birthplace, home_address, phone_number, email,
    father_last_name, father_first_name, father_middle_name, father_suffix,
    mother_last_name, mother_first_name, mother_middle_name, mother_suffix
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssssssssssssssss",
    $_POST['last_name'],
    $_POST['first_name'],
    $_POST['middle_name'],
    $_POST['suffix'],
    $_POST['gender'],
    $_POST['marital_status'],
    $_POST['birthdate'],
    $_POST['nationality'],
    $_POST['birthplace'],
    $_POST['home_address'],
    $_POST['phone_number'],
    $_POST['email'],
    $_POST['father_last_name'],
    $_POST['father_first_name'],
    $_POST['father_middle_name'],
    $_POST['father_suffix'],
    $_POST['mother_last_name'],
    $_POST['mother_first_name'],
    $_POST['mother_middle_name'],
    $_POST['mother_suffix']
);

$stmt->execute();
$record_id = $stmt->insert_id;
$stmt->close();

if (!empty($_POST['dep_name'])) {
    $stmt = $conn->prepare(
        "INSERT INTO tbl_dependents (record_id, dep_name, relationship, date_of_birth)
         VALUES (?, ?, ?, ?)"
    );

    for ($i = 0; $i < count($_POST['dep_name']); $i++) {
        if ($_POST['dep_name'][$i] !== '') {
            $stmt->bind_param(
                "isss",
                $record_id,
                $_POST['dep_name'][$i],
                $_POST['dep_rel'][$i],
                $_POST['dep_dob'][$i]
            );
            $stmt->execute();
        }
    }
    $stmt->close();
}

$stmt = $conn->prepare(
    "INSERT INTO tbl_employment_info (
        record_id, profession, year_started, se_monthly_earnings,
        foreign_address, ofw_monthly_earnings,
        spouse_ss_number, spouse_income
    ) VALUES (?,?,?,?,?,?,?,?)"
);

$stmt->bind_param(
    "isssdssd",
    $record_id,
    $_POST['profession'],
    $_POST['year_started'],
    $_POST['monthly_earnings'],
    $_POST['foreign_address'],
    $_POST['ofw_monthly_earnings'],
    $_POST['spouse_ss_number'],
    $_POST['spouse_income']
);

$stmt->execute();
$stmt->close();

$stmt = $conn->prepare(
    "INSERT INTO tbl_certification (record_id, printed_name, signature, cert_date)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param(
    "isss",
    $record_id,
    $_POST['printed_name'],
    $_POST['signature'],
    $_POST['cert_date']
);

try {
    if ($is_update) {
        $sql = "UPDATE tbl_record SET 
                last_name=?, first_name=?, middle_name=?, suffix=?,
                gender=?, marital_status=?, birthdate=?, nationality=?,
                birthplace=?, home_address=?, phone_number=?, email=?,
                father_last_name=?, father_first_name=?, father_middle_name=?, father_suffix=?,
                mother_last_name=?, mother_first_name=?, mother_middle_name=?, mother_suffix=?
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssssssssssi", 
            $_POST['last_name'], $_POST['first_name'], $_POST['middle_name'], $_POST['suffix'],
            $_POST['gender'], $_POST['marital_status'], $_POST['birthdate'], $_POST['nationality'],
            $_POST['birthplace'], $_POST['home_address'], $_POST['phone_number'], $_POST['email'],
            $_POST['father_last_name'], $_POST['father_first_name'], $_POST['father_middle_name'], $_POST['father_suffix'],
            $_POST['mother_last_name'], $_POST['mother_first_name'], $_POST['mother_middle_name'], $_POST['mother_suffix'],
            $record_id
        );
    } else {
        $sql = "INSERT INTO tbl_record (
            last_name, first_name, middle_name, suffix,
            gender, marital_status, birthdate, nationality,
            birthplace, home_address, phone_number, email,
            father_last_name, father_first_name, father_middle_name, father_suffix,
            mother_last_name, mother_first_name, mother_middle_name, mother_suffix
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssssssssss",
            $_POST['last_name'], $_POST['first_name'], $_POST['middle_name'], $_POST['suffix'],
            $_POST['gender'], $_POST['marital_status'], $_POST['birthdate'], $_POST['nationality'],
            $_POST['birthplace'], $_POST['home_address'], $_POST['phone_number'], $_POST['email'],
            $_POST['father_last_name'], $_POST['father_first_name'], $_POST['father_middle_name'], $_POST['father_suffix'],
            $_POST['mother_last_name'], $_POST['mother_first_name'], $_POST['mother_middle_name'], $_POST['mother_suffix']
        );
    }

    $stmt->execute();
    if (!$is_update) {
        $record_id = $stmt->insert_id;
    }
    $stmt->close();

    if ($is_update) {
        $conn->query("DELETE FROM tbl_dependents WHERE record_id = $record_id");
    }

    if (!empty($_POST['dep_name'])) {
        $stmt = $conn->prepare("INSERT INTO tbl_dependents (record_id, dep_name, relationship, date_of_birth) VALUES (?, ?, ?, ?)");
        for ($i = 0; $i < count($_POST['dep_name']); $i++) {
            if (trim($_POST['dep_name'][$i]) !== '') {
                $stmt->bind_param("isss", $record_id, $_POST['dep_name'][$i], $_POST['dep_rel'][$i], $_POST['dep_dob'][$i]);
                $stmt->execute();
            }
        }
        $stmt->close();
    }

    if ($is_update) {
        $sql_emp = "UPDATE tbl_employment_info SET 
                    profession=?, year_started=?, se_monthly_earnings=?,
                    foreign_address=?, ofw_monthly_earnings=?,
                    spouse_ss_number=?, spouse_income=?
                    WHERE record_id = ?";
        $stmt_emp = $conn->prepare($sql_emp);
        $stmt_emp->bind_param(
            "ssdssdsi", 
            $_POST['profession'], $_POST['year_started'], $_POST['monthly_earnings'],
            $_POST['foreign_address'], $_POST['ofw_monthly_earnings'],
            $_POST['spouse_ss_number'], $_POST['spouse_income'],
            $record_id
        );
    } else {
        $sql_emp = "INSERT INTO tbl_employment_info (record_id, profession, year_started, se_monthly_earnings, foreign_address, ofw_monthly_earnings, spouse_ss_number, spouse_income) VALUES (?,?,?,?,?,?,?,?)";
        $stmt_emp = $conn->prepare($sql_emp);
        $stmt_emp->bind_param("isssdssd", $record_id, $_POST['profession'], $_POST['year_started'], $_POST['monthly_earnings'], $_POST['foreign_address'], $_POST['ofw_monthly_earnings'], $_POST['spouse_ss_number'], $_POST['spouse_income']);
    }
    $stmt_emp->execute();
    $stmt_emp->close();

    if ($is_update) {
        $stmt_cert = $conn->prepare("UPDATE tbl_certification SET printed_name=?, signature=?, cert_date=? WHERE record_id=?");
        $stmt_cert->bind_param("sssi", $_POST['printed_name'], $_POST['signature'], $_POST['cert_date'], $record_id);
    } else {
        $stmt_cert = $conn->prepare("INSERT INTO tbl_certification (record_id, printed_name, signature, cert_date) VALUES (?, ?, ?, ?)");
        $stmt_cert->bind_param("isss", $record_id, $_POST['printed_name'], $_POST['signature'], $_POST['cert_date']);
    }
    $stmt_cert->execute();
    $stmt_cert->close();

    $conn->commit();
    echo json_encode(['success' => true, 'id' => $record_id, 'message' => 'Record saved successfully!']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>