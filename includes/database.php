<?php
$conn = new mysqli("localhost", "root", "", "patenio_form");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>