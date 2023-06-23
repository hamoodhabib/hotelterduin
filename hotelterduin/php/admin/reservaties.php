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

// Handle email sending
if (isset($_POST['sendEmail'])) {
    $reservationId = $_POST['reservation_id'];
    
    try {
        // Retrieve the reservation details
        $reservationSql = "SELECT r.check_in_date, r.check_out_date, u.username, u.address, u.email, u.phone, rt.room_type
                           FROM reservations AS r
                           INNER JOIN users AS u ON r.user_id = u.user_id
                           INNER JOIN rooms AS rt ON r.room_id = rt.room_id
                           WHERE r.reservation_id = :reservation_id";
        $reservationStmt = $PDO->prepare($reservationSql);
        $reservationStmt->bindParam(':reservation_id', $reservationId);
        $reservationStmt->execute();
        $reservation = $reservationStmt->fetch(PDO::FETCH_ASSOC);
        
        // Compose email body
        $emailBody = "<html><head><style>" . file_get_contents("../../css/factuur.css") . "</style></head><body><div class='main'>";
        $emailBody .= "<h1>Factuur</h1>";
        $emailBody .= "<ul>";
        $emailBody .= "<li><strong>Username:</strong> " . $reservation['username'] . "</li>";
        $emailBody .= "<li><strong>Address:</strong> " . $reservation['address'] . "</li>";
        $emailBody .= "<li><strong>Email:</strong> " . $reservation['email'] . "</li>";
        $emailBody .= "<li><strong>Phone:</strong> " . $reservation['phone'] . "</li>";
        $emailBody .= "<li><strong>Room Type:</strong> " . $reservation['room_type'] . "</li>";
        $emailBody .= "<li><strong>Check-in Date:</strong> " . $reservation['check_in_date'] . "</li>";
        $emailBody .= "<li><strong>Check-out Date:</strong> " . $reservation['check_out_date'] . "</li>";
        $emailBody .= "</ul>";
        $emailBody .= "</div></body></html>";
        
        // Send email
        if (sendEmail($reservation['email'], $reservation['username'], 'Invoice', $emailBody)) {
            echo "Email sent successfully.";
        } else {
            echo "Failed to send email.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
                                        <select name="room_type">
                                            <?php
                                            // Fetch all room types from the rooms table
                                            $roomTypesSql = "SELECT room_type FROM rooms";
                                            $roomTypesStmt = $PDO->prepare($roomTypesSql);
                                            $roomTypesStmt->execute();
                                            $roomTypes = $roomTypesStmt->fetchAll(PDO::FETCH_COLUMN);

                                            // Generate the options for the dropdown
                                            foreach ($roomTypes as $roomType) {
                                                $selected = ($roomType === $reservation['room_type']) ? 'selected' : '';
                                                echo "<option value=\"$roomType\" $selected>$roomType</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="text" name="check_in_date" value="<?php echo $reservation['check_in_date']; ?>"></td>
                                    <td><input type="text" name="check_out_date" value="<?php echo $reservation['check_out_date']; ?>"></td>
                                    <td>
                                        <button type="submit" name="submit" value="update">Update</button>
                                        <button type="submit" name="submit" value="delete">Delete</button>
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
