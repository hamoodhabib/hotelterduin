<?php

session_start();
require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteId = $_POST['user_id'];

    $stmt = $PDO->prepare("DELETE FROM users WHERE user_id = :id");
    $stmt->bindParam(':id', $deleteId);

    try {
        $stmt->execute();
        header("Location: ../../php/admin/klanten.php?success=Klant is succesvol verwijderd");
        exit();
    } catch (PDOException $e) {
        echo $e->getMessage();
        header("Location: ../../php/admin/klanten.php?error=Unknown error occurred");
        exit();
    }
}

?>
