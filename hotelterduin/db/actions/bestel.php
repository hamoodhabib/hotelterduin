<?php

require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get the cart information from the database
        $sql = "SELECT * FROM winkelwagen";
        $stmt = $PDO->prepare($sql);
        $stmt->execute();
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Insert the cart information into the Bestellingen table
        $sql = "INSERT INTO bestellingen (productnaam, prijs, aantal) VALUES (:productnaam, :prijs, :aantal)";
        $stmt = $PDO->prepare($sql);
        foreach ($cart_items as $item) {
            $stmt->bindParam(':productnaam', $item['naam']);
            $stmt->bindParam(':prijs', $item['prijs']);
            $stmt->bindParam(':aantal', $item['voorraad']);
            $stmt->execute();
        }

        // Clear the winkelwagen table
        $sql = "DELETE FROM winkelwagen";
        $stmt = $PDO->prepare($sql);
        $stmt->execute();

        // Redirect to the bestelling page
        header('Location: ../../php/clientside/bestelling.php');
        exit;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>
