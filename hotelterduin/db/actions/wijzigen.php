<?php

// Check of het formulier is verzonden
if (isset($_POST['submit'])) {
    // Maak een verbinding met de database
    include "../db/config.php";
    // Haal de product-ID op
    $id = $_POST['id'];
    
    // Haal de bestaande gegevens van het product op uit de database
    $stmt = $PDO->prepare('SELECT * FROM product WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    
    // Update de gegevens van het product in de database
    $naam = $_POST['naam'];
    $prijs = $_POST['prijs'];
    $voorraad = $_POST['voorraad'];
    
    $stmt = $PDO->prepare('UPDATE product SET naam = ?, prijs = ?, voorraad = ? WHERE id = ?');
    $stmt->execute([$naam, $prijs, $voorraad, $id]);
    
    // Update de foto van het product als er een nieuwe foto is geüpload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        // Verwijder de oude foto van de server
        unlink('images/' . $product['foto']);
        
        // Sla de nieuwe foto op in de map op de server
        $foto = uniqid() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'images/' . $foto);
        
        // Update de foto van het product in de database
        $stmt = $PDO->prepare('UPDATE product SET foto = ? WHERE id = ?');
        $stmt->execute([$foto, $id]);
    }
    
    // Redirect naar de pagina voor het bekijken van het product
    header('Location: product.php?id=' . $id);
    exit();
}
