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
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .reservation-form {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .reservation-form h1 {
            margin: 0;
            margin-bottom: 20px;
            color: darkcyan;
        }

        .reservation-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .reservation-form select,
        .reservation-form input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .reservation-form input[type="submit"] {
            padding: 10px 20px;
            background-color: darkcyan;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .reservation-form input[type="submit"]:hover {
            background-color: #00b3b3;
        }

        .rooms-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .room {
  background-color: #f5f5f5;
  padding: 20px;
  margin: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  text-align: center;
  position: relative;
  overflow: hidden;
}

        .room h3 {
            margin: 0;
            margin-bottom: 10px;
            color: darkcyan;
        }

        .room p {
            margin: 0;
            margin-bottom: 5px;
            font-size: 14px;
        }



.room-image {
  width: 100%;
  height: 200px; /* Set the desired height for the room image */
}

.room-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 5px;
  margin-bottom: 10px;
}

    </style>
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
                <input type="date" name="check_in_date" required>
                <br>
                <label for="check_out_date">Check-out Date:</label>
                <input type="date" name="check_out_date" required>
                <br>
                <input type="submit" name="submit" value="Make Reservation">
            </form>
        </div>

        <div class="rooms-container">
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
