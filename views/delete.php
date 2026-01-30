<?php
// Start connection
$conn = new mysqli("localhost", "root", "", "patenio_form");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for ID from either POST (AJAX) or GET (URL Link)
$id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);

if ($id > 0) {
    $conn->begin_transaction();
    try {
        // 1. Delete dependent records first
        $conn->query("DELETE FROM tbl_dependents WHERE record_id = $id");
        $conn->query("DELETE FROM tbl_employment_info WHERE record_id = $id");
        $conn->query("DELETE FROM tbl_certification WHERE record_id = $id");

        // 2. Delete main record
        $stmt = $conn->prepare("DELETE FROM tbl_record WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $conn->commit();

        // 3. DECIDE HOW TO RESPOND:
        // If it was an AJAX request (from script.js), send JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || isset($_POST['id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        } else {
            // If it was a regular link (from index.php), redirect back
            header("Location: ../index.php?deleted=success");
            exit;
        }

    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid ID.");
}
?>