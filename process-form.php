<?php
// 1. Set the response type to JSON
header('Content-Type: application/json');

// 2. Database Configuration
$host = "localhost";
$username = "root"; 
$password = ""; // Default for XAMPP is empty
$dbname = "patenio_form";

// 3. Connect to the Database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// 4. Collect and Sanitize Data
// We use null coalescing (??) to avoid "Undefined Index" notices
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

// 5. Basic Server-Side Validation
if (empty($last_name) || empty($first_name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
    exit;
}

// 6. Prepare the SQL Statement (Prevents SQL Injection)
$sql = "INSERT INTO tbl_record (last_name, first_name, middle_name, suffix, gender, marital_status, birthdate, nationality, birthplace, home_address, phone_number, email) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssss", 
    $last_name, $first_name, $middle_name, $suffix, $gender, 
    $marital_status, $birthdate, $nationality, $birthplace, 
    $home_address, $phone_number, $email
);

// 7. Execute and Respond
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Record saved successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving record: ' . $stmt->error]);
}

// 8. Close connections
$stmt->close();
$conn->close();
?>