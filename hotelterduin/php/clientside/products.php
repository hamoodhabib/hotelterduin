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

    // Validate the reservation dates
    $currentDate = date('Y-m-d');
    $next30Days = date('Y-m-d', strtotime('+30 days'));

    if ($check_in_date >= $currentDate && $check_out_date > $check_in_date && $check_out_date <= $next30Days) {
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
    } else {
        // Redirect to an error page or display an error message
        header("Location: reservatie.php?error=Invalid reservation dates");
        exit;
    }
}

// Define the image paths for each room
$imagePaths = [
    'room1' => '../../images/logo.jpg',
    'room2' => '../../images/logo.png',
    'room3' => 'path_to_image3.jpg',
    // Add more rooms and image paths as needed
];

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
    <link rel="stylesheet" href="../../css/reservatie.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <script src="../../js/products.js"></script>
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>
    <br><br>
    <div class="container">
        <div class="reservation-form">
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
                <input type="date" name="check_in_date" min="<?php echo date('Y-m-d'); ?>" required>
                <br>
                <label for="check_out_date">Check-out Date:</label>
                <input type="date" name="check_out_date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                <br>
                <input type="submit" name="submit" value="Make Reservation">
            </form>
        </div>

        <div class="rooms-container">
        <br>
        <center>
            <h1>Make a Reservation</h1>
                <p>Make your reservation now and enjoy the exotic rooms. <br> Click the images.</p>
        </center>
        <br>
            <?php foreach ($rooms as $room) { ?>
                <div class="room">
                    <div class="room-image">
                        <?php
                        // Get the image path for the room
                        $imagePath = isset($imagePaths[$room['room_type']]) ? $imagePaths[$room['room_type']] : '../../images/logo.png';
                        ?>
                        <img src="<?php echo $imagePath; ?>" alt="Room Image">
                    </div>
                    <h3>Room Type: <?php echo $room['room_type']; ?></h3>
                    <p>Description: <?php echo $room['description']; ?></p>
                    <p>Availability: <?php echo $room['available']; ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
    <footer>
        <?php include '../../templates/footer.php'; ?>
    </footer>

</body>

</html>
