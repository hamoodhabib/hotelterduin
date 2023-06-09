<?php

// session_start();

require_once dirname(__FILE__) . '/../config.php';

$db = new Db();
$PDO = $db->getPDO();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $roomType = $_POST['room_type'];
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Insert reservation into the database
    try {
        $stmt = $PDO->prepare("INSERT INTO reservations (room_type, check_in_date, check_out_date, first_name, last_name, email, phone)
                               VALUES (:room_type, :check_in_date, :check_out_date, :first_name, :last_name, :email, :phone)");
        $stmt->bindParam(':room_type', $roomType);
        $stmt->bindParam(':check_in_date', $checkInDate);
        $stmt->bindParam(':check_out_date', $checkOutDate);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        // Redirect to success page
        header('Location: thankyou.php');
        exit;
    } catch (PDOException $e) {
        // Display error message
        echo 'Error: ' . $e->getMessage();
    }
}

?>
