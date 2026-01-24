<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "patenio_form");
if ($conn->connect_error) {
    echo json_encode(['success' => false]);
    exit;
}

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

$stmt->execute();
$stmt->close();


$conn->commit();
$conn->close();

echo json_encode(['success' => true]);
