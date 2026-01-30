<?php
header('Content-Type: application/json');

// Database Connection
$conn = new mysqli("localhost", "root", "", "patenio_form");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if this is an Update or a New Entry
$record_id = isset($_POST['record_id']) && !empty($_POST['record_id']) ? intval($_POST['record_id']) : null;
$is_update = ($record_id !== null);

$conn->begin_transaction();

try {
    // 1. Handle tbl_record (Main Personal Details)
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
    
    if (!$stmt->execute()) {
        throw new Exception("Error saving record: " . $stmt->error);
    }
    
    if (!$is_update) {
        $record_id = $stmt->insert_id;
    }
    $stmt->close();

    // 2. Handle tbl_dependents
    // If updating, clear existing dependents first to avoid duplicates
    if ($is_update) {
        $conn->query("DELETE FROM tbl_dependents WHERE record_id = $record_id");
    }

    if (!empty($_POST['dep_name'])) {
        $stmt_dep = $conn->prepare("INSERT INTO tbl_dependents (record_id, dep_name, relationship, date_of_birth) VALUES (?, ?, ?, ?)");
        for ($i = 0; $i < count($_POST['dep_name']); $i++) {
            $name = trim($_POST['dep_name'][$i]);
            if ($name !== '') {
                $rel = $_POST['dep_rel'][$i] ?? '';
                $dob = !empty($_POST['dep_dob'][$i]) ? $_POST['dep_dob'][$i] : null;
                $stmt_dep->bind_param("isss", $record_id, $name, $rel, $dob);
                $stmt_dep->execute();
            }
        }
        $stmt_dep->close();
    }

    // 3. Handle tbl_employment_info
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
        $stmt_emp->bind_param(
            "isssdssd", 
            $record_id, $_POST['profession'], $_POST['year_started'], $_POST['monthly_earnings'], 
            $_POST['foreign_address'], $_POST['ofw_monthly_earnings'], $_POST['spouse_ss_number'], $_POST['spouse_income']
        );
    }
    $stmt_emp->execute();
    $stmt_emp->close();

    // 4. Handle tbl_certification
    if ($is_update) {
        $stmt_cert = $conn->prepare("UPDATE tbl_certification SET printed_name=?, signature=?, cert_date=? WHERE record_id=?");
        $stmt_cert->bind_param("sssi", $_POST['printed_name'], $_POST['signature'], $_POST['cert_date'], $record_id);
    } else {
        $stmt_cert = $conn->prepare("INSERT INTO tbl_certification (record_id, printed_name, signature, cert_date) VALUES (?, ?, ?, ?)");
        $stmt_cert->bind_param("isss", $record_id, $_POST['printed_name'], $_POST['signature'], $_POST['cert_date']);
    }
    $stmt_cert->execute();
    $stmt_cert->close();

    // Success!
    $conn->commit();
    echo json_encode([
        'success' => true, 
        'id' => $record_id, 
        'message' => $is_update ? 'Record updated successfully!' : 'New record created successfully!'
    ]);

} catch (Exception $e) {
    // If anything fails, undo all database changes
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>