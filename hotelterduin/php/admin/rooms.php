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
    <style>
        .table-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main>
        <div class="table-container">
            <h1>Add Room</h1>
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
                    <tr>
                        <form action="" method="post">
                            <td><input type="text" name="room_type" placeholder="Type of room.." required></td>
                            <td><input type="text" name="description"></td>
                            <td><input type="number" name="available" required></td>
                            <td><input type="submit" name="add" value="Add Room"></td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-container">
            <h1>Edit Room</h1>
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
