<?php

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

if (isset($_POST['submit'])) {
// Get the form input values
    $naam = $_POST['naam'];
    $prijs = $_POST['prijs'];

    $id = $_POST['id'];
    $voorraad = $_POST['voorraad'];

    // Retrieve the current stock value from the database
    $stock_query = $PDO->prepare("SELECT voorraad FROM product WHERE id = ?");
    $stock_query->execute([$id]);
    $stock = $stock_query->fetchColumn();
    
    // Calculate the new stock value after adding to the cart
    if ($stock >= $voorraad) {
      // Prepare and execute the SQL query to insert the data into the cart table
        $sql = "INSERT INTO winkelwagen (naam, prijs, voorraad) VALUES (:naam, :prijs, :voorraad)";
        $stmt = $PDO->prepare($sql);
        $stmt->bindParam(':naam', $naam);
        $stmt->bindParam(':prijs', $prijs);
        $stmt->bindParam(':voorraad', $voorraad);
        $stmt->execute();

        $new_stock = intval($stock) - intval($voorraad);

        // $new_prijs = $prijs * $voorraad;
    
        // Update the stock value in the database
        $update_query = $PDO->prepare("UPDATE product SET voorraad = ? WHERE id = ?");
        $update_query->execute([$new_stock, $id]);
        // Close the database connection
        $PDO = null;
        // Redirect the user to the cart page or display a success message
        header('Location: ../../php/clientside/wagen.php');
        exit();
    } else {
        echo "Niet genoeg voorraad";
        header('Location: ../../php/clientside/products.php');
    }
}

?>
