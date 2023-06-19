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
    <title>Factuur</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <style>
        @media print {
            .print-button,
            header {
                display: none;
            }
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }

        .main {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 10px;
            color: #555;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .print-button {
            padding: 10px 20px;
            background-color: darkcyan;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 20px auto;
            border-radius: 5px;
        }

        .print-button:hover {
            background-color: #00b3b3;
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
            <h1>Factuur <?php echo $counter; ?></h1>
            <ul>
                <li><strong>Username:</strong> <?php echo $invoice['username']; ?></li>
                <li><strong>Address:</strong> <?php echo $invoice['address']; ?></li>
                <li><strong>Email:</strong> <?php echo $invoice['email']; ?></li>
                <li><strong>Phone:</strong> <?php echo $invoice['phone']; ?></li>
                <li><strong>Room Type:</strong> <?php echo $invoice['room_type']; ?></li>
                <li><strong>Check-in Date:</strong> <?php echo $invoice['check_in_date']; ?></li>
                <li><strong>Check-out Date:</strong> <?php echo $invoice['check_out_date']; ?></li>
            </ul>
            <hr>
            <?php $counter++; ?>
        <?php } ?>

        <button class="print-button" onclick="window.print()">Print Factuur</button>
    </main>
</body>

</html>
