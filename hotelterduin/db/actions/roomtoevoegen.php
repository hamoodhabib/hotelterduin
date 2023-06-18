<?php
session_start();

require_once '../../db/config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the user has the necessary role to add rooms
if ($_SESSION['role'] !== 'admin') {
    echo "You do not have permission to access this page.";
    exit;
}

$db = new Db();
$PDO = $db->getPDO();

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve room data from the form
    $roomType = $_POST['room_type'];
    $description = $_POST['description'];
    $available = $_POST['available'];

    // Insert room data into the rooms table
    $insertSql = "INSERT INTO rooms (room_type, description, available) VALUES (:roomType, :description, :available)";
    $insertStmt = $PDO->prepare($insertSql);
    $insertStmt->bindParam(':roomType', $roomType);
    $insertStmt->bindParam(':description', $description);
    $insertStmt->bindParam(':available', $available);
    $insertStmt->execute();

    // Redirect to a success page or display a success message
    header("Location: rooms.php?success=Room added successfully");
    exit;
}
?>