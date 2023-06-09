<?php

session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Reservations</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>
    <br>
    <main>
        <article>
            <?php 
            
            try {
                $sql = "SELECT * FROM reservations";
                $stmt = $PDO->prepare($sql);
                $stmt->execute();
                $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            foreach ($reservations as $reservation) { ?>
                <div class="formulier">
                    <form action="../../db/actions/productverwijderen.php" method="post">
                        <div class="formulier-text">
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                            <br><br>
                            <label for="room_type">Room Type:</label>
                            <input type="text" name="room_type" value="<?php echo $reservation['room_type']; ?>">
                            <br><br>
                            <label for="check_in_date">Check-in Date:</label>
                            <input type="text" name="check_in_date" value="<?php echo $reservation['check_in_date']; ?>">
                            <br><br>
                            <label for="check_out_date">Check-out Date:</label>
                            <input type="text" name="check_out_date" value="<?php echo $reservation['check_out_date']; ?>">
                            <br><br>
                            <label for="first_name">First Name:</label>
                            <input type="text" name="first_name" value="<?php echo $reservation['first_name']; ?>">
                            <br><br>
                            <label for="last_name">Last Name:</label>
                            <input type="text" name="last_name" value="<?php echo $reservation['last_name']; ?>">
                            <br><br>
                            <label for="email">Email:</label>
                            <input type="text" name="email" value="<?php echo $reservation['email']; ?>">
                            <br><br>
                            <label for="phone">Phone:</label>
                            <input type="text" name="phone" value="<?php echo $reservation['phone']; ?>">
                            <br /><br />
                            <input type="submit" name="submit" value="Update Reservation">
                            <br><br>
                            <input type="submit" name="submit" value="Delete Reservation">
                        </div>
                    </form>
                </div>
            <?php } ?>
        </article>
        <br>
    </main>
</body>

</html>
