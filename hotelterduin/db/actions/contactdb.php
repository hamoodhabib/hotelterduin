<?php

// connect to the database
require_once 'config.php';
$db = new Db();
$PDO = $db->getPDO();

// check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get form data and insert into database
    $naam = $_POST['naam'];
    $email = $_POST['email'];
    $vraag = $_POST['vraag'];
    try {
        $stmt = $PDO->prepare("INSERT INTO contact (naam, email, vraag) VALUES (:naam, :email, :vraag)");
        $stmt->bindParam(':naam', $naam);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':vraag', $vraag);
        $stmt->execute();
        header('Location: ../php/contact.php?success=true');
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        header('Location: ../php/contact.php?error=true');
    }
}

// close database connection
$PDO = null;
?>
