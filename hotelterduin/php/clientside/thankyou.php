<?php

session_start();
require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

$user_id = $_SESSION['user_id'];

try {
    $invoiceSql = "SELECT r.check_in_date, r.check_out_date, u.username, u.address, u.email, u.phone, rt.room_type
                   FROM reservations AS r
                   INNER JOIN users AS u ON r.user_id = u.user_id
                   INNER JOIN rooms AS rt ON r.room_id = rt.room_id
                   WHERE u.user_id = :user_id";
    $invoiceStmt = $PDO->prepare($invoiceSql);
    $invoiceStmt->bindParam(':user_id', $user_id);
    $invoiceStmt->execute();
    $invoices = $invoiceStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Invoice</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <style>
        @media print {
            .print-button,
            header {
                display: none;
            }
        }
    </style>
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main class="main">
        <?php $counter = 1; ?>
        <?php foreach ($invoices as $invoice) { ?>
        <h1>Invoice <?php echo $counter; ?></h1>
        <ul>
            <li>Username: <?php echo $invoice['username']; ?></li>
            <li>Address: <?php echo $invoice['address']; ?></li>
            <li>Email: <?php echo $invoice['email']; ?></li>
            <li>Phone: <?php echo $invoice['phone']; ?></li>
            <li>Room Type: <?php echo $invoice['room_type']; ?></li>
            <li>Check-in Date: <?php echo $invoice['check_in_date']; ?></li>
            <li>Check-out Date: <?php echo $invoice['check_out_date']; ?></li>
        </ul>
        <hr>
            <?php $counter++; ?>
        <?php } ?>

        <button class="print-button" onclick="window.print()">Print Invoice</button>
    </main>
</body>

</html>
