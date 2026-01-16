<?php
$host = 'localhost';
$dbname = 'patenio_form';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(['success' => false, 'errors' => ["Connection failed: " . $e->getMessage()]]));
}

$errors = [];
$success = false;
$record_id = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Personal Details
    $last_name = trim($_POST['last_name'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $marital_status = $_POST['marital_status'] ?? '';
    $nationality = trim($_POST['nationality'] ?? '');
    $birthplace = trim($_POST['birthplace'] ?? '');
    $home_address = trim($_POST['home_address'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    // Self-employed
    $profession = trim($_POST['profession'] ?? '');
    $year_started = trim($_POST['year_started'] ?? '');
    $monthly_earnings = !empty($_POST['monthly_earnings']) ? str_replace(',', '', $_POST['monthly_earnings']) : null;
    $monthly_earnings_float = $monthly_earnings !== null ? floatval($monthly_earnings) : null;
    
    // OFW
    $foreign_address = trim($_POST['foreign_address'] ?? '');
    $ofw_monthly_earnings = !empty($_POST['ofw_monthly_earnings']) ? str_replace(',', '', $_POST['ofw_monthly_earnings']) : null;
    $ofw_monthly_earnings_float = $ofw_monthly_earnings !== null ? floatval($ofw_monthly_earnings) : null;
    $flexi_fund = $_POST['flexi_fund'] ?? null;
    
    // Non-working spouse
    $spouse_ss_number = trim($_POST['spouse_ss_number'] ?? '');
    $spouse_income = !empty($_POST['spouse_income']) ? str_replace(',', '', $_POST['spouse_income']) : null;
    $spouse_income_float = $spouse_income !== null ? floatval($spouse_income) : null;
    
    // Certification
    $printed_name = trim($_POST['printed_name'] ?? '');
    $signature = trim($_POST['signature'] ?? '');
    $cert_date = $_POST['cert_date'] ?? null;
    
    // Validation
    if (empty($last_name)) $errors[] = "Last Name is required.";
    if (empty($first_name)) $errors[] = "First Name is required.";
    if (empty($birthdate)) $errors[] = "Date of Birth is required.";
    if (empty($gender) || !in_array($gender, ['Male', 'Female'])) $errors[] = "Valid Gender selection is required.";
    if (empty($marital_status) || !in_array($marital_status, ['Single', 'Married', 'Widowed', 'Divorced'])) $errors[] = "Valid Marital Status selection is required.";
    if (empty($nationality)) $errors[] = "Nationality is required.";
    if (empty($birthplace)) $errors[] = "Place of Birth is required.";
    if (empty($home_address)) $errors[] = "Home Address is required.";
    if (empty($phone_number)) $errors[] = "Mobile Number is required.";
    if (empty($email)) $errors[] = "Email Address is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";

    if (empty($errors)) {
        try {
            // Insert into tbl_record
            $sql = "INSERT INTO tbl_record (last_name, first_name, middle_name, suffix, gender, marital_status, birthdate, nationality, birthplace, home_address, phone_number, email, profession, year_started, monthly_earnings, foreign_address, ofw_monthly_earnings, flexi_fund, spouse_ss_number, spouse_income, printed_name, signature, cert_date) 
                    VALUES (:last_name, :first_name, :middle_name, :suffix, :gender, :marital_status, :birthdate, :nationality, :birthplace, :home_address, :phone_number, :email, :profession, :year_started, :monthly_earnings, :foreign_address, :ofw_monthly_earnings, :flexi_fund, :spouse_ss_number, :spouse_income, :printed_name, :signature, :cert_date)";

            $stmt = $pdo->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':middle_name', $middle_name);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':marital_status', $marital_status);
            $stmt->bindParam(':birthdate', $birthdate);
            $stmt->bindParam(':nationality', $nationality);
            $stmt->bindParam(':birthplace', $birthplace);
            $stmt->bindParam(':home_address', $home_address);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':profession', $profession);
            $stmt->bindParam(':year_started', $year_started);
            $stmt->bindParam(':monthly_earnings', $monthly_earnings_float, PDO::PARAM_STR);
            $stmt->bindParam(':foreign_address', $foreign_address);
            $stmt->bindParam(':ofw_monthly_earnings', $ofw_monthly_earnings_float, PDO::PARAM_STR);
            $stmt->bindParam(':flexi_fund', $flexi_fund);
            $stmt->bindParam(':spouse_ss_number', $spouse_ss_number);
            $stmt->bindParam(':spouse_income', $spouse_income_float, PDO::PARAM_STR);
            $stmt->bindParam(':printed_name', $printed_name);
            $stmt->bindParam(':signature', $signature);
            $stmt->bindParam(':cert_date', $cert_date);

            if ($stmt->execute()) {
                $record_id = $pdo->lastInsertId();
                
                // Insert dependents
                if (isset($_POST['dependent_name']) && is_array($_POST['dependent_name'])) {
                    $dependent_names = $_POST['dependent_name'];
                    $dependent_relationships = $_POST['dependent_relationship'] ?? [];
                    $dependent_dobs = $_POST['dependent_dob'] ?? [];
                    
                    $dependent_sql = "INSERT INTO tbl_dependents (record_id, dependent_name, relationship, dependent_dob) 
                                     VALUES (:record_id, :dependent_name, :relationship, :dependent_dob)";
                    $dependent_stmt = $pdo->prepare($dependent_sql);
                    
                    for ($i = 0; $i < count($dependent_names); $i++) {
                        $dependent_name = trim($dependent_names[$i] ?? '');
                        $dependent_relationship = trim($dependent_relationships[$i] ?? '');
                        $dependent_dob = !empty($dependent_dobs[$i]) ? $dependent_dobs[$i] : null;
                        
                        if (!empty($dependent_name)) {
                            $dependent_stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
                            $dependent_stmt->bindParam(':dependent_name', $dependent_name);
                            $dependent_stmt->bindParam(':relationship', $dependent_relationship);
                            $dependent_stmt->bindParam(':dependent_dob', $dependent_dob);
                            $dependent_stmt->execute();
                        }
                    }
                }
                
                $success = true;
            }
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

header('Content-Type: application/json');
echo json_encode([
    'success' => $success,
    'errors' => $errors,
    'record_id' => $record_id
]);
?>