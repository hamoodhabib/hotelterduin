<?php

session_start();
require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

$deleteid = ($_GET['id']);
                    
$sql = "DELETE FROM winkelwagen WHERE ID ='$deleteid'";
$delete = $PDO->prepare($sql);
try {
    $delete->execute();
    header("Location: ../../php/clientside/wagen.php?success=Product is succesvol verwijderd");
    exit();
} catch (PDOException $e) {
    echo $e->getMessage();
    // header("Location: winkelwagen.php?error=unknown error occurred");
    // exit();
}

?>
