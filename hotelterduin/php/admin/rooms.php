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
    <style>
        .main {
            margin-top: 50px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        form {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"] {
            width: 300px;
            padding: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .room {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .room h3 {
            font-size: 18px;
            margin-bottom: 5px;
            cursor: pointer;
        }

        .room p {
            margin-bottom: 5px;
        }

        .room form {
            margin-top: 10px;
            display: inline-block;
        }

        .update-form {
            display: none;
            margin-top: 10px;
        }

        .update-form label {
            display: inline-block;
            margin-bottom: 5px;
        }

        .update-form input[type="text"],
        .update-form input[type="number"] {
            width: 200px;
            padding: 5px;
            margin-bottom: 10px;
        }

        .update-form input[type="submit"] {
            padding: 6px 10px;
        }

        .room:hover .update-form {
            display: block;
        }

        .delete-button {
            background-color: #f44336;
            color: #fff;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            margin-left: 5px;
        }
    </style>
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

        <h1>Edit/Delete</h1>
        <?php foreach ($rooms as $room) { ?>
            <div class="room">
                <h3 onclick="toggleUpdateForm(<?php echo $room['room_id']; ?>)"><?php echo $room['room_type']; ?></h3>
                <p>Description: <?php echo $room['description']; ?></p>
                <p>Availability: <?php echo $room['available']; ?></p>
                <form id="update-form-<?php echo $room['room_id']; ?>" class="update-form" action="" method="post">
                    <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                    <label for="room_type">Room Type:</label>
                    <input type="text" name="room_type" value="<?php echo $room['room_type']; ?>" required>
                    <br>
                    <label for="description">Description:</label>
                    <input type="text" name="description" value="<?php echo $room['description']; ?>">
                    <br>
                    <label for="available">Availability:</label>
                    <input type="number" name="available" value="<?php echo $room['available']; ?>" required>
                    <br>
                    <input type="submit" name="update" value="Update">
                </form>
                <form action="" method="post">
                    <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                    <input type="submit" name="remove" class="delete-button" value="Delete">
                </form>
            </div>
        <?php } ?>

        <script>
            function toggleUpdateForm(roomId) {
                var updateForm = document.getElementById('update-form-' + roomId);
                if (updateForm.style.display === 'none') {
                    updateForm.style.display = 'block';
                } else {
                    updateForm.style.display = 'none';
                }
            }
        </script>
    </main>
</body>

</html>
