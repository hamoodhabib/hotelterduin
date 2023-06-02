<?php

// Connect to the database using PDO
require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();
// Get the product information from the form
$id = $_POST['id'];
$voorraad = $_POST['voorraad'];
//Retrieve the current stock value from the database
$stock_query = $PDO->prepare("SELECT voorraad FROM product WHERE id = ?");
$stock_query->execute([$id]);
$stock = $stock_query->fetchColumn();



// Calculate the new stock value after adding to the cart
$new_stock = $stock + $voorraad;

// Update the stock value in the database
$update_query = $PDO->prepare("UPDATE product SET voorraad = ? WHERE id = ?");
$update_query->execute([$new_stock, $product_id]);

echo "Stock value updated successfully!";
?>
