<?php

// Connect to the database
require_once '../config.php';
$db = new Db();
$PDO = $db->getPDO();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and insert into the database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    
    // Retrieve the user ID from the session
    session_start();
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $PDO->prepare("INSERT INTO Contacts (user_id, name, email, phone, message) VALUES (:user_id, :name, :email, :phone, :message)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
        header('Location: ../../php/clientside/contact.php');
        exit;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        header('Location: ../../php/clientside/contact.php');
        exit;
    }
}

// Close the database connection
$PDO = null;
?>
