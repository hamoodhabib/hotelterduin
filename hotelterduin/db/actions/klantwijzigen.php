<?php

require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

if (isset($_POST['submit'])) {
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users SET username=:username, password=:password, address=:address, email=:email, phone=:phone WHERE user_id=:user_id";
    
    $stmt = $PDO->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':user_id', $userId);

    try {
        $stmt->execute();
        header("Location: ../../php/admin/klanten.php?success=Klantgegevens zijn gewijzigd");
        exit();
    } catch (PDOException $e) {
        echo $e->getMessage();
        header("Location: ../../php/admin/klanten.php?error=Unknown error occurred");
        exit();
    }
} else {
    header("Location: ../../php/admin/klanten.php");
    exit();
}

?>
