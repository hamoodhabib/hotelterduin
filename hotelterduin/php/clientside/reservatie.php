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

            // Validate the reservation dates
            $currentDate = date('Y-m-d');
            $next30Days = date('Y-m-d', strtotime('+30 days'));

            if ($newCheckInDate >= $currentDate && $newCheckInDate <= $check_out_date) {
                if ($newCheckOutDate > $newCheckInDate && $newCheckOutDate <= $next30Days) {
                    // Update the reservation details
                    $updateSql = "UPDATE reservations SET room_id = :room_id, check_in_date = :check_in_date, check_out_date = :check_out_date WHERE reservation_id = :reservation_id";
                    $updateStmt = $PDO->prepare($updateSql);
                    $updateStmt->bindParam(':room_id', $newRoomId);
                    $updateStmt->bindParam(':check_in_date', $newCheckInDate);
                    $updateStmt->bindParam(':check_out_date', $newCheckOutDate);
                    $updateStmt->bindParam(':reservation_id', $reservation_id);
                    $updateStmt->execute();

                    // Redirect or display a success message
                    $_SESSION['success_message'] = "Reservation updated successfully.";
                    header("Location: reservatie.php");
                    exit;
                } else {
                    echo "Invalid check-out date. Please select a date within the allowed range.";
                }
            } else {
                echo "Invalid check-in date. Please select a date within the allowed range.";
            }
        } elseif ($_POST['submit'] === 'Delete Reservation') {
            // Update the available count of the room
            updateRoomAvailability($room_type, $reservation['available'] + 1);

            // Delete the reservation
            $deleteSql = "DELETE FROM reservations WHERE reservation_id = :reservation_id";
            $deleteStmt = $PDO->prepare($deleteSql);
            $deleteStmt->bindParam(':reservation_id', $reservation_id);
            $deleteStmt->execute();

            // Redirect or display a success message
            $_SESSION['success_message'] = "Reservation deleted successfully.";
            header("Location: reservatie.php");
            exit;
        }
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
    <script src="../../js/products.js"></script>
</head>
<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>
    <br>
    
    <main>
        <center>
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
                                        <select name="room_id" required>
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
                                        <input type="date" name="check_in_date" id="check_in_date" min="<?php echo $currentDate; ?>" max="<?php echo $check_out_date; ?>" required>
                                        <br>
                                        <label for="check_out_date">Select New Check-out Date:</label>
                                        <input type="date" name="check_out_date" id="check_out_date" min="<?php echo $check_in_date; ?>" max="<?php echo $next30Days; ?>" required>
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
        </center>
    </main>
    <script>
        // Client-side validation to prevent selecting invalid check-out dates
        document.addEventListener("DOMContentLoaded", function () {
            var checkInDateInput = document.getElementById('check_in_date');
            var checkOutDateInput = document.getElementById('check_out_date');

            checkInDateInput.addEventListener("input", function () {
                var checkInDate = new Date(this.value);
                var checkOutDate = new Date(checkOutDateInput.value);

                if (checkOutDate <= checkInDate) {
                    checkOutDateInput.value = "";
                }

                checkOutDateInput.min = formatDate(checkInDate, 1);
            });

            function formatDate(date, offsetDays) {
                date.setDate(date.getDate() + offsetDays);
                var year = date.getFullYear();
                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                var day = ("0" + date.getDate()).slice(-2);
                return year + "-" + month + "-" + day;
            }
        });
    </script>
</body>
</html>
