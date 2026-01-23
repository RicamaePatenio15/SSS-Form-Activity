<?php
header('Content-Type: application/json');

$host = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "patenio_form";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$last_name      = $_POST['last_name'] ?? '';
$first_name     = $_POST['first_name'] ?? '';
$middle_name    = $_POST['middle_name'] ?? '';
$suffix         = $_POST['suffix'] ?? '';
$gender         = $_POST['gender'] ?? '';
$marital_status = $_POST['marital_status'] ?? '';
$birthdate      = $_POST['birthdate'] ?? '';
$nationality    = $_POST['nationality'] ?? '';
$birthplace     = $_POST['birthplace'] ?? '';
$home_address   = $_POST['home_address'] ?? '';
$phone_number   = $_POST['phone_number'] ?? '';
$email          = $_POST['email'] ?? '';

$f_last   = $_POST['father_last_name'] ?? '';
$f_first  = $_POST['father_first_name'] ?? '';
$f_mid    = $_POST['father_middle_name'] ?? '';
$f_suffix = $_POST['father_suffix'] ?? '';

$m_last   = $_POST['mother_last_name'] ?? '';
$m_first  = $_POST['mother_first_name'] ?? '';
$m_mid    = $_POST['mother_middle_name'] ?? '';
$m_suffix = $_POST['mother_suffix'] ?? '';

$sql = "INSERT INTO tbl_record (
    last_name, first_name, middle_name, suffix, gender, marital_status, 
    birthdate, nationality, birthplace, home_address, phone_number, email,
    father_last_name, father_first_name, father_middle_name, father_suffix,
    mother_last_name, mother_first_name, mother_middle_name, mother_suffix
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ssssssssssssssssssss", 
    $last_name, $first_name, $middle_name, $suffix, $gender, $marital_status, 
    $birthdate, $nationality, $birthplace, $home_address, $phone_number, $email,
    $f_last, $f_first, $f_mid, $f_suffix,
    $m_last, $m_first, $m_mid, $m_suffix
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Record and Parent info saved!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>