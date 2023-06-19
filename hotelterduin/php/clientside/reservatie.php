<?php
session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

// Function to update the available count of a room type
function updateRoomAvailability($room_type, $available)
{
    $updateSql = "UPDATE rooms SET available = :available WHERE room_type = :room_type";
    $updateStmt = $GLOBALS['PDO']->prepare($updateSql);
    $updateStmt->bindParam(':available', $available);
    $updateStmt->bindParam(':room_type', $room_type);
    $updateStmt->execute();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $reservation_id = $_POST['reservation_id'];

    // Get the current reservation details
    $reservationSql = "SELECT * FROM reservations AS r
                       INNER JOIN rooms AS rt ON r.room_id = rt.room_id
                       WHERE r.reservation_id = :reservation_id";
    $reservationStmt = $PDO->prepare($reservationSql);
    $reservationStmt->bindParam(':reservation_id', $reservation_id);
    $reservationStmt->execute();
    $reservation = $reservationStmt->fetch(PDO::FETCH_ASSOC);

    if ($reservation) {
        $room_type = $reservation['room_type'];
        $check_in_date = $reservation['check_in_date'];
        $check_out_date = $reservation['check_out_date'];

        if ($_POST['submit'] === 'Update Reservation') {
            $newRoomId = $_POST['room_id'];
            $newCheckInDate = $_POST['check_in_date'];
            $newCheckOutDate = $_POST['check_out_date'];

            // Get the room type of the new room
            $roomTypeSql = "SELECT room_type FROM rooms WHERE room_id = :room_id";
            $roomTypeStmt = $PDO->prepare($roomTypeSql);
            $roomTypeStmt->bindParam(':room_id', $newRoomId);
            $roomTypeStmt->execute();
            $newRoomType = $roomTypeStmt->fetchColumn();

            // Update the available count of the rooms
            if ($newRoomType !== $room_type) {
                updateRoomAvailability($room_type, $reservation['available'] + 1);
                updateRoomAvailability($newRoomType, $reservation['available']);
            }

            // Update the reservation details
            $updateSql = "UPDATE reservations SET room_id = :room_id, check_in_date = :check_in_date, check_out_date = :check_out_date WHERE reservation_id = :reservation_id";
            $updateStmt = $PDO->prepare($updateSql);
            $updateStmt->bindParam(':room_id', $newRoomId);
            $updateStmt->bindParam(':check_in_date', $newCheckInDate);
            $updateStmt->bindParam(':check_out_date', $newCheckOutDate);
            $updateStmt->bindParam(':reservation_id', $reservation_id);
            $updateStmt->execute();
        } elseif ($_POST['submit'] === 'Delete Reservation') {
            // Update the available count of the room
            updateRoomAvailability($room_type, $reservation['available'] + 1);

            // Delete the reservation
            $deleteSql = "DELETE FROM reservations WHERE reservation_id = :reservation_id";
            $deleteStmt = $PDO->prepare($deleteSql);
            $deleteStmt->bindParam(':reservation_id', $reservation_id);
            $deleteStmt->execute();
        }

        // Redirect or display a success message
        header("Location: gegevens.php?success=Reservation updated/deleted successfully");
        exit;
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Customer Reservations</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/reservatie.css">
    <style>
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .reservations {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .formulier {
            margin: 10px;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .formulier h1 {
            margin: 0;
            margin-bottom: 20px;
            color: darkcyan;
        }
        
        .formulier-text {
            display: flex;
            flex-direction: column;
        }
        
        .formulier-text h2 {
            margin: 0;
            font-size: 24px;
        }
        
        .formulier-text p {
            margin: 0;
            font-size: 16px;
        }
        
        .reservation-actions {
            margin-top: 20px;
        }
        
        .reservation-form {
            margin-top: 20px;
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>
    <br>
    
    <main>
        <h1>My reservation(s)</h1>
        <div class="reservations">
            <?php 
            try {
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    $sql = "SELECT r.*, rt.room_type FROM reservations AS r INNER JOIN rooms AS rt ON r.room_id = rt.room_id WHERE r.user_id = :user_id";
                    $stmt = $PDO->prepare($sql);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();
                    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($reservations as $reservation) { ?>
                        <div class="formulier">
                            <form action="" method="post">
                                <div class="formulier-text">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                                    <h2><?php echo $reservation['room_type']; ?></h2>
                                    <p>Check-in Date: <?php echo $reservation['check_in_date']; ?></p>
                                    <p>Check-out Date: <?php echo $reservation['check_out_date']; ?></p>
                                    <div class="reservation-actions">
                                        <label for="room_id">Select New Room:</label>
                                        <select name="room_id">
                                            <option value="" disabled selected>Select a room</option>
                                            <?php
                                            // Fetch available rooms from the database
                                            $sql = "SELECT * FROM rooms WHERE available > 0";
                                            $stmt = $PDO->prepare($sql);
                                            $stmt->execute();
                                            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            
                                            foreach ($rooms as $room) {
                                                echo '<option value="' . $room['room_id'] . '">' . $room['room_type'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <br>
                                        <label for="check_in_date">Select New Check-in Date:</label>
                                        <input type="date" name="check_in_date">
                                        <br>
                                        <label for="check_out_date">Select New Check-out Date:</label>
                                        <input type="date" name="check_out_date">
                                        <br><br>
                                        <input type="submit" name="submit" value="Update Reservation">
                                        <br><br>
                                        <input type="submit" name="submit" value="Delete Reservation">
                                    </div>
                                </div>
                            </form>
                            <a href="thankyou.php"><button>Factuur hier</button> </a> 
                        </div>
                    <?php }
                } else {
                    echo "<p>No reservations found.</p>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </div>
        <br>
    </main>
</body>
</html>
