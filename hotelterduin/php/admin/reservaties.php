<?php

session_start();

require_once '../../db/config.php';
require_once '../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$db = new Db();
$PDO = $db->getPDO();

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

try {
    $sql = "SELECT r.*, rt.room_type, u.username FROM reservations AS r INNER JOIN rooms AS rt ON r.room_id = rt.room_id INNER JOIN users AS u ON r.user_id = u.user_id";
    $stmt = $PDO->prepare($sql);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

// Function to update the available count of a room type
function updateRoomAvailability($room_id, $available)
{
    $updateSql = "UPDATE rooms SET available = :available WHERE room_id = :room_id";
    $updateStmt = $GLOBALS['PDO']->prepare($updateSql);
    $updateStmt->bindParam(':available', $available);
    $updateStmt->bindParam(':room_id', $room_id);
    $updateStmt->execute();
}

// Send email function
function sendEmail($toEmail, $toName, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com'; // Replace with your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hamoodhabibitesting@outlook.com'; // Replace with your email address
        $mail->Password   = 'hamoodhabibi69'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('hamoodhabibitesting@outlook.com', 'Factuur Hotel ter Duin'); // Replace with your email address and name
        $mail->addAddress($toEmail, $toName);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Email could not be sent. Error: {$mail->ErrorInfo}";
        return false;
    }
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
        $room_id = $reservation['room_id'];
        $room_type = $reservation['room_type'];

        if ($_POST['submit'] === 'Update') {
            $new_room_type = $_POST['room_type'];

            // Update the available count of the rooms
            if ($new_room_type !== $room_type) {
                updateRoomAvailability($room_id, $reservation['available'] + 1);
                updateRoomAvailability($_POST['new_room_id'], $reservation['available']);
            }

            // Update the reservation details
            $updateSql = "UPDATE reservations SET room_id = :room_id WHERE reservation_id = :reservation_id";
            $updateStmt = $PDO->prepare($updateSql);
            $updateStmt->bindParam(':room_id', $_POST['new_room_id']);
            $updateStmt->bindParam(':reservation_id', $reservation_id);
            $updateStmt->execute();

            // Redirect or display a success message
            $_SESSION['success_message'] = "Reservation updated successfully.";
            header("Location: reservaties.php");
            exit;
        } elseif ($_POST['submit'] === 'Delete') {
            // Update the available count of the room
            updateRoomAvailability($room_id, $reservation['available'] + 1);

            // Delete the reservation
            $deleteSql = "DELETE FROM reservations WHERE reservation_id = :reservation_id";
            $deleteStmt = $PDO->prepare($deleteSql);
            $deleteStmt->bindParam(':reservation_id', $reservation_id);
            $deleteStmt->execute();

            // Redirect or display a success message
            $_SESSION['success_message'] = "Reservation deleted successfully.";
            header("Location: reservaties.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Reservations</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/admin.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>
    <br>
    <main>
        <article>
            <div class="table-container">
                <table>
                    <center>
                        <h1>All Reservations</h1>
                        <p>You can update anything here and delete reservations. <br> Don't randomly press buttons.</p>

                    </center>
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Username</th>
                            <th>Room Type</th>
                            <th>Check-in Date</th>
                            <th>Check-out Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation) { ?>
                            <tr>
                                <form action="" method="post">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                                    <td><?php echo $reservation['reservation_id']; ?></td>
                                    <td><?php echo $reservation['username']; ?></td>
                                    <td>
                                        <select name="new_room_id">
                                            <?php
                                            // Fetch available rooms from the database
                                            $roomsSql = "SELECT * FROM rooms WHERE available > 0";
                                            $roomsStmt = $PDO->prepare($roomsSql);
                                            $roomsStmt->execute();
                                            $rooms = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);

                                            // Generate the options for the dropdown
                                            foreach ($rooms as $room) {
                                                $selected = ($room['room_id'] === $reservation['room_id']) ? 'selected' : '';
                                                echo "<option value=\"" . $room['room_id'] . "\" $selected>" . $room['room_type'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="text" name="check_in_date" value="<?php echo $reservation['check_in_date']; ?>"></td>
                                    <td><input type="text" name="check_out_date" value="<?php echo $reservation['check_out_date']; ?>"></td>
                                    <td>
                                        <button type="submit" name="submit" value="Update">Update</button>
                                        <button type="submit" name="submit" value="Delete">Delete</button>
                                        <button type="submit" name="sendEmail" value="true" class="mail-button">Send Email</button>
                                    </td>
                                </form>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </article>
        <br>
    </main>
    <?php if (isset($_SESSION['success_message'])) { ?>
        <script>
            alert("<?php echo $_SESSION['success_message']; ?>");
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php } ?>
    <script>
        var mailButtons = document.getElementsByClassName("mail-button");
        var printButton = document.getElementsByClassName("print-button")[0];

        for (var i = 0; i < mailButtons.length; i++) {
            mailButtons[i].addEventListener("click", function() {
                printButton.style.display = "none";
            });
        }
    </script>
</body>

</html>
