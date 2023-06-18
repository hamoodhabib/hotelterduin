<!DOCTYPE html>
<html>
<head>
    <title>Customer Reservations</title>
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/reservatie.css">
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
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT r.*, rt.room_type FROM reservations AS r INNER JOIN rooms AS rt ON r.room_id = rt.room_id WHERE r.user_id = :user_id";
                $stmt = $PDO->prepare($sql);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            foreach ($reservations as $reservation) { ?>
                <div class="formulier">
                    <form action="../../db/actions/reservatieclient.php" method="post">
                        <div class="formulier-text">
                            <h1>My reservation(s)</h1>
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                            <div class="room-image">
                                <!-- Replace the placeholder image path with the actual image path -->
                                <img src="path/to/room_image.jpg" alt="Room Image">
                            </div>
                            <div class="room-details">
                                <h2><?php echo $reservation['room_type']; ?></h2>
                                <p>Check-in Date: <?php echo $reservation['check_in_date']; ?></p>
                                <p>Check-out Date: <?php echo $reservation['check_out_date']; ?></p>
                            </div>
                            <div class="reservation-actions">
                                <input type="submit" name="submit" value="Update Reservation">
                                <br><br>
                                <input type="submit" name="submit" value="Delete Reservation">
                            </div>
                        </div>
                    </form>
                    <a href="thankyou.php"><button>Factuur hier</button> </a> 
                </div>
            <?php } ?>
        </article>
        <br>
    </main>
</body>
</html>
