<?php

session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

try {
    $sql = "SELECT r.*, rt.room_type, u.username FROM reservations AS r INNER JOIN rooms AS rt ON r.room_id = rt.room_id INNER JOIN users AS u ON r.user_id = u.user_id";
    $stmt = $PDO->prepare($sql);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}
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
            <?php foreach ($reservations as $reservation) { ?>
                <div class="formulier">
                    <form action="../../db/actions/reservatieadmin.php" method="post">
                        <div class="formulier-text">
                            <h1>Reservation</h1>
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                            <br><br>
                            <label for="room_type">Room Type:</label>
                            <select name="room_type">
                                <?php
                                // Fetch all room types from the rooms table
                                $roomTypesSql = "SELECT room_type FROM rooms";
                                $roomTypesStmt = $PDO->prepare($roomTypesSql);
                                $roomTypesStmt->execute();
                                $roomTypes = $roomTypesStmt->fetchAll(PDO::FETCH_COLUMN);
                                
                                // Generate the options for the dropdown
                                foreach ($roomTypes as $roomType) {
                                    $selected = ($roomType === $reservation['room_type']) ? 'selected' : '';
                                    echo "<option value=\"$roomType\" $selected>$roomType</option>";
                                }
                                ?>
                            </select>
                            <br><br>
                            <label for="check_in_date">Check-in Date:</label>
                            <input type="text" name="check_in_date" value="<?php echo $reservation['check_in_date']; ?>">
                            <br><br>
                            <label for="check_out_date">Check-out Date:</label>
                            <input type="text" name="check_out_date" value="<?php echo $reservation['check_out_date']; ?>">
                            <br><br>
                            <label for="username">Username:</label>
                            <input type="text" name="username" value="<?php echo $reservation['username']; ?>">
                            <br><br>
                            <input type="submit" name="submit" value="Update Reservation">
                            <br><br>
                            <input type="submit" name="submit" value="Delete Reservation">
                            <br><br>
                        </div>
                    </form>
                    <a href="../clientside/thankyou.php"><button>Print Invoice</button> </a>
                </div>
            <?php } ?>
        </article>
        <br>
    </main>
</body>

</html>
