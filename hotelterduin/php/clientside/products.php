<?php
session_start();

require_once '../../db/config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$db = new Db();
$PDO = $db->getPDO();

// Handle reservation submission
if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];

    // Insert the reservation into the reservations table
    $insertSql = "INSERT INTO reservations (user_id, room_id, check_in_date, check_out_date)
                  VALUES (:user_id, :room_id, :check_in_date, :check_out_date)";
    $insertStmt = $PDO->prepare($insertSql);
    $insertStmt->bindParam(':user_id', $user_id);
    $insertStmt->bindParam(':room_id', $room_id);
    $insertStmt->bindParam(':check_in_date', $check_in_date);
    $insertStmt->bindParam(':check_out_date', $check_out_date);
    $insertStmt->execute();

    // Update the availability of the selected room
    $updateSql = "UPDATE rooms SET available = available - 1 WHERE room_id = :room_id";
    $updateStmt = $PDO->prepare($updateSql);
    $updateStmt->bindParam(':room_id', $room_id);
    $updateStmt->execute();

    // Redirect to a success page or display a success message
    header("Location: reservatie.php?success=Reservation made successfully");
    exit;
}

// Fetch available rooms from the database
$sql = "SELECT * FROM rooms WHERE available > 0";
$stmt = $PDO->prepare($sql);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Make Reservation</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>
    <main class="main">
        <h1>Make Reservation</h1>
        <form action="" method="post">
            <label for="room_id">Select Room:</label>
            <select name="room_id" required>
                <option value="" disabled selected>Select a room</option>
                <?php foreach ($rooms as $room) { ?>
                    <option value="<?php echo $room['room_id']; ?>">
                        <?php echo $room['room_type']; ?>
                    </option>
                <?php } ?>
            </select>
            <br>
            <label for="check_in_date">Check-in Date:</label>
            <input type="date" name="check_in_date" required>
            <br>
            <label for="check_out_date">Check-out Date:</label>
            <input type="date" name="check_out_date" required>
            <br>
            <input type="submit" name="submit" value="Make Reservation">
        </form>
    </main>

        
    <main class="main">
        <h1>Rooms</h1>
        <?php foreach ($rooms as $room) { ?>
            <div class="room">
                <h3>Room Type: <?php echo $room['room_type']; ?></h3>
                <p>Description: <?php echo $room['description']; ?></p>
                <p>Availability: <?php echo $room['available']; ?></p>
            </div>
        <?php } ?>
    </main>
</body>
</html>
