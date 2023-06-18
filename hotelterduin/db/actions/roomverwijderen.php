<?php

session_start();

require_once '../../db/config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the user has the necessary role to remove rooms
if ($_SESSION['role'] !== 'employee') {
    echo "You do not have permission to access this page.";
    exit;
}

$db = new Db();
$PDO = $db->getPDO();

// Handle room removal
if (isset($_POST['remove'])) {
    $roomId = $_POST['room_id'];

    // Delete the room from the rooms table
    $deleteSql = "DELETE FROM rooms WHERE room_id = :roomId";
    $deleteStmt = $PDO->prepare($deleteSql);
    $deleteStmt->bindParam(':roomId', $roomId);
    $deleteStmt->execute();

    // Redirect to a success page or display a success message
    header("Location: ../../php/admin/rooms.php?success=Room removed successfully");
    exit;
}

// Fetch all rooms from the database
$sql = "SELECT * FROM rooms";
$stmt = $PDO->prepare($sql);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
