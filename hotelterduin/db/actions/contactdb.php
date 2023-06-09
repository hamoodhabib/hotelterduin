<?php

// connect to the database
require_once '../config.php';
$db = new Db();
$PDO = $db->getPDO();

// check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get form data and insert into database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    
    try {
        $stmt = $PDO->prepare("INSERT INTO Contacts (name, email, phone, message) VALUES (:name, :email, :phone, :message)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
        header('Location: ../../php/clientside/contact.php');
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        header('Location: ../../php/clientside/contact.php');
    }
}

// close database connection
$PDO = null;
?>
