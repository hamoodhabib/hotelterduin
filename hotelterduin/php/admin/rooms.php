<?php
session_start();

require_once '../../db/config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../../php/clientside/login.php");
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

// Handle room removal
if (isset($_POST['remove'])) {
    $roomID = $_POST['room_id'];

    // Delete the room from the rooms table
    $deleteSql = "DELETE FROM rooms WHERE room_id = :roomID";
    $deleteStmt = $PDO->prepare($deleteSql);
    $deleteStmt->bindParam(':roomID', $roomID);
    $deleteStmt->execute();

    // Redirect to a success page or display a success message
    header("Location: rooms.php?success=Room removed successfully");
    exit;
}

// Handle room update
if (isset($_POST['update'])) {
    $roomID = $_POST['room_id'];
    $roomType = $_POST['room_type'];
    $description = $_POST['description'];
    $available = $_POST['available'];

    // Update the room in the rooms table
    $updateSql = "UPDATE rooms SET room_type = :roomType, description = :description, available = :available WHERE room_id = :roomID";
    $updateStmt = $PDO->prepare($updateSql);
    $updateStmt->bindParam(':roomType', $roomType);
    $updateStmt->bindParam(':description', $description);
    $updateStmt->bindParam(':available', $available);
    $updateStmt->bindParam(':roomID', $roomID);
    $updateStmt->execute();

    // Redirect to a success page or display a success message
    header("Location: rooms.php?success=Room updated successfully");
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
    <link rel="stylesheet" href="../../css/admin.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main>
        <div class="table-container">
        <center>
            <h1>Add Room</h1>
                <p>You can update anything here and delete rooms. <br> Don't randomly press buttons.</p>
        </center>
            <form action="" method="post">
                <table>
                    <tr>
                        <td><input type="text" name="room_type" placeholder="Type of room.." required></td>
                        <td><input type="text" name="description"></td>
                        <td><input type="number" name="available" required></td>
                        <td><input type="submit" name="add" value="Add Room"></td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="table-container">
            <center>
            <h1>Edit Room</h1>
            </center>
            <table>
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>Description</th>
                        <th>Availability</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room) { ?>
                        <tr>
                            <form action="" method="post">
                                <td><input type="text" name="room_type" value="<?php echo $room['room_type']; ?>" required></td>
                                <td><input type="text" name="description" value="<?php echo $room['description']; ?>"></td>
                                <td><input type="number" name="available" value="<?php echo $room['available']; ?>" required></td>
                                <td>
                                    <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                                    <input type="submit" name="update" value="Update">
                                    <input type="submit" name="remove" class="delete-button" value="Delete">
                                </td>
                            </form>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
