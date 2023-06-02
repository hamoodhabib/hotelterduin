<?php

require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

// Controleren of het formulier is verzonden
if (isset($_POST['submit'])) {
    // De waarden uit de formulier velden ophalen
    $ID = $_POST['id'];
    $voornaam = $_POST['voornaam'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $adres = $_POST['adres'];
    $postcode = $_POST['postcode'];
    $woonplaats = $_POST['woonplaats'];
    $email = $_POST['email'];

    // Query om de gegevens in de database bij te werken
    $sql = "UPDATE klant SET voornaam=:voornaam, password=:password, adres=:adres, postcode=:postcode, woonplaats=:woonplaats, email=:email WHERE id=:id";
    
    // Bereid de query voor op uitvoering en bind de waarden aan de parameters
    $stmt = $PDO->prepare($sql);
    $stmt->bindParam(':voornaam', $voornaam);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':adres', $adres);
    $stmt->bindParam(':postcode', $postcode);
    $stmt->bindParam(':woonplaats', $woonplaats);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $ID);

    // Voer de query uit
    $stmt->execute();
    ?>
    <script>
    alert("Klantgegevens zijn gewijzigd");
    </script>
    <?php
    header("Location: ../../php/admin/klanten.php");
    // Sluit de database connectie
} else {
    ?>
    <script>
    alert("Klantgegevens zijn gewijzigd");
    </script>
    <?php
    header("Location: ../../php/admin/klanten.php");
}

// Redirect naar de homepage

?>
