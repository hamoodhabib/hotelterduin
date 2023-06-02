<?php

session_start();
require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

$deleteid = ($_POST['id']);
                    
$sql = "DELETE FROM klant WHERE ID='$deleteid'";
$delete = $PDO->prepare($sql);
try {
    $delete->execute();
    header("Location: ../../php/admin/klanten.php?success=Klant is succesvol verwijderd");
    exit();
} catch (PDOException $e) {
    echo $e->getMessage();
    header("Location: ../../php/admin/klanten.php?error=unknown error occurred");
    exit();
}

?>
