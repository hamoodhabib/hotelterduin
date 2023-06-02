<?php

session_start();
require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

$voornaam = ($_POST['voornaam']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$adres = ($_POST['adres']);
$postcode = ($_POST['postcode']);
$woonplaats = ($_POST['woonplaats']);
$email = ($_POST['email']);

                    
try {
    $sql = "INSERT INTO klant(voornaam, password, adres, postcode, woonplaats, email) 
                        VALUES ('$voornaam', '$password', '$adres', '$postcode', '$woonplaats', '$email')";
    $stmt = $PDO->prepare($sql);
    $stmt->execute();
    if ($stmt) {
        header("Location: ../../php/clientside/homepage.php?success=Succesvol een klant toegevoegd");
        exit();
    } else {
        header("Location: ../../php/clientside/register.php?error=unknown error occurred");
        exit();
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    echo "Try again later.";
    exit();
}

?>
