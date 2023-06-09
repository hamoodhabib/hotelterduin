<?php

session_start();
require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

try {
    $sql = "SELECT * FROM rooms";
    $stmt = $PDO->prepare($sql);
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Thank You</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main class="main">
        <h1>Thank You for Your Reservation</h1>
        <p>Your reservation has been successfully submitted. We look forward to welcoming you at our hotel.</p>
        <p>Please find below the details of your reservation:</p>
        <ul>
            <li>Room Type: <?php echo $reservation['room_type']; ?></li>
            <li>Check-in Date: <?php echo $reservation['check_in_date']; ?></li>
            <li>Check-out Date: <?php echo $reservation['check_out_date']; ?></li>
            <li>First Name: <?php echo $reservation['first_name']; ?></li>
            <li>Last Name: <?php echo $reservation['last_name']; ?></li>
            <li>Email: <?php echo $reservation['email']; ?></li>
            <li>Phone: <?php echo $reservation['phone']; ?></li>
        </ul>

        <button onclick="window.print()">Print Reservation</button>
    </main>
</body>

</html>
