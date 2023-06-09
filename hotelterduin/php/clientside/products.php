<?php

session_start();

require_once '../../db/config.php';
require_once '../../db/actions/reserveren.php';

$db = new Db();
$PDO = $db->getPDO();

// Fetch room data from the database
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
    <title>Reservation</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main>
        <div class="formulier">
            <h1>Make a Reservation</h1>
            <form method="post" action="">
                <label for="room_type">Room Type:</label>
                <select id="room_type" name="room_type">
                    <option value="single">Single Room</option>
                    <option value="double">Double Room</option>
                    <option value="suite">Suite</option>
                </select>
                <br /><br />
                <label for="check_in_date">Check-in Date:</label>
                <input type="date" id="check_in_date" name="check_in_date" required>
                <br /><br />
                <label for="check_out_date">Check-out Date:</label>
                <input type="date" id="check_out_date" name="check_out_date" required>
                <br /><br />
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
                <br /><br />
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
                <br /><br />
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br /><br />
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
                <br /><br />
                <input type="submit" value="Make Reservation">
            </form>
        </div>
    </main>
</body>

</html>
