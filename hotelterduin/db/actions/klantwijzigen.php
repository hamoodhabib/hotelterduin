<?php

require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

if (isset($_POST['submit'])) {
    $loginId = $_POST['login_id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "UPDATE Login SET username=:username, password=:password WHERE login_id=:login_id";
    
    $stmt = $PDO->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':login_id', $loginId);

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
