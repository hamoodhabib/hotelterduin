<?php

session_start();

if (isset($_POST['submit'])) {
    require_once '../config.php';

    $db = new Db();
    $PDO = $db->getPDO();

    $naam = $_POST['naam'];
    $prijs = $_POST['prijs'];
    $voorraad = $_POST['voorraad'];

    $foto_name = $_FILES['foto']['name'];
    $foto_location = $_FILES['foto']['tmp_name'];
    $foto_type = $_FILES['foto']['type'];
    $foto_size = $_FILES['foto']['size'];

    $allowed_extensions = array("jpg","jpeg","png");
    $extension = pathinfo($foto_name, PATHINFO_EXTENSION);
    if (!in_array($extension, $allowed_extensions)) {
        header("Location: ../../php/admin/producten.php?error=Alleen JPG, JPEG en PNG bestanden zijn toegestaan");
        exit();
    }

    if ($foto_size > 1000000) {
        header("Location: ../../php/admin/producten.php?error=De bestandsgrootte mag niet groter zijn dan 1MB");
        exit();
    }

    $foto_content = file_get_contents($foto_location);
    $foto_content = base64_encode($foto_content);
    try {
        $stmt = $PDO->prepare("UPDATE product SET naam=:naam, prijs=:prijs, foto=:foto, voorraad=:voorraad WHERE id=:id");
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->bindParam(':naam', $naam);
        $stmt->bindParam(':prijs', $prijs);
        $stmt->bindParam(':foto', $foto_content, PDO::PARAM_LOB);
        $stmt->bindParam(':voorraad', $voorraad);
        $stmt->execute();

        header("Location: ../../php/admin/producten.php?success=Succesvol een product toegevoegd");
        exit();
    } catch (PDOException $e) {
        header("Location: ../../php/admin/producten.php?error=" . $e->getMessage());
        exit();
    }
}
?>
