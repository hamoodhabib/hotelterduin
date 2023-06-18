<?php
session_start();

require_once '../../db/config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the user has the necessary role to add rooms
if ($_SESSION['role'] !== 'employee') {
    echo "You do not have permission to access this page.";
    exit;
}

$db = new Db();
$PDO = $db->getPDO();

// Handle room addition
if (isset($_POST['add'])) {
    $roomType = $_POST['room_type'];
    $description = $_POST['description'];
    $available = $_POST['available'];

    // Insert the new room into the rooms table
    $insertSql = "INSERT INTO rooms (room_type, description, available) VALUES (:roomType, :description, :available)";
    $insertStmt = $PDO->prepare($insertSql);
    $insertStmt->bindParam(':roomType', $roomType);
    $insertStmt->bindParam(':description', $description);
    $insertStmt->bindParam(':available', $available);
    $insertStmt->execute();

    // Redirect to a success page or display a success message
    header("Location: rooms.php?success=Room added successfully");
    exit;
}

// Fetch all rooms from the database
$sql = "SELECT * FROM rooms";
$stmt = $PDO->prepare($sql);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rooms</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main class="main">
        <h1>Add Room</h1>
        <form action="" method="post">
            <label for="room_type">Room Type:</label>
            <input type="text" name="room_type" required>
            <br>
            <label for="description">Description:</label>
            <input type="text" name="description">
            <br>
            <label for="available">Availability:</label>
            <input type="number" name="available" required>
            <br>
            <input type="submit" name="add" value="Add Room">
        </form>

        <h1>Rooms</h1>
        <?php foreach ($rooms as $room) { ?>
            <div class="room">
                <h3>Room Type: <?php echo $room['room_type']; ?></h3>
                <p>Description: <?php echo $room['description']; ?></p>
                <p>Availability: <?php echo $room['available']; ?></p>
                <form action="" method="post">
                    <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                    <input type="submit" name="remove" value="Remove Room">
                </form>
            </div>
        <?php } ?>
    </main>
</body>

</html>
