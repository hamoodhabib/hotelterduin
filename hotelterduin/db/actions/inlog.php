<?php

require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

if (isset($_POST['submit'])) {
    $voornaam = $_POST['voornaam'];
    $password = $_POST['password'];
    
    
    $stmt = $PDO->prepare("SELECT * FROM klant WHERE voornaam = :voornaam");
    $stmt->bindParam(':voornaam', $voornaam);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['voornaam'] = $voornaam;
            $_SESSION['id'] = $user['id'];
            $_SESSION['admin'] = $user['admin'];
            header('Location:../../php/clientside/products.php');
            exit;
        } else {
            header('Location:../../php/clientside/login.php?error=Incorrect username or password');
        }
    } else {
        header('Location:../../php/clientside/login.php?error=Incorrect username or password');
    }   
}

?>
