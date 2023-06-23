<?php
session_start();
require_once '../../db/config.php';
require_once '../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$db = new Db();
$PDO = $db->getPDO();

$user_id = $_SESSION['user_id'];

try {
    $invoiceSql = "SELECT r.check_in_date, r.check_out_date, u.username, u.address, u.email, u.phone, rt.room_type
                   FROM reservations AS r
                   INNER JOIN users AS u ON r.user_id = u.user_id
                   INNER JOIN rooms AS rt ON r.room_id = rt.room_id
                   WHERE u.user_id = :user_id";
    $invoiceStmt = $PDO->prepare($invoiceSql);
    $invoiceStmt->bindParam(':user_id', $user_id);
    $invoiceStmt->execute();
    $invoices = $invoiceStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
    $toEmail = $_POST['toEmail'];
    $toName  = $_POST['toName'];
    $subject = "Invoice";
    $body    = "<html><head><style>" . file_get_contents("../../css/factuur.css") . "</style></head><body><div class='main'>";
    foreach ($invoices as $invoice) {
        $body .= "<h1>Factuur</h1>";
        $body .= "<ul>";
        $body .= "<li><strong>Username:</strong> " . $invoice['username'] . "</li>";
        $body .= "<li><strong>Address:</strong> " . $invoice['address'] . "</li>";
        $body .= "<li><strong>Email:</strong> " . $invoice['email'] . "</li>";
        $body .= "<li><strong>Phone:</strong> " . $invoice['phone'] . "</li>";
        $body .= "<li><strong>Room Type:</strong> " . $invoice['room_type'] . "</li>";
        $body .= "<li><strong>Check-in Date:</strong> " . $invoice['check_in_date'] . "</li>";
        $body .= "<li><strong>Check-out Date:</strong> " . $invoice['check_out_date'] . "</li>";
        $body .= "</ul>";
        $body .= "<hr>";
    }
    $body .= "</div></body></html>";

    if (sendEmail($toEmail, $toName, $subject, $body)) {
        echo "Email sent successfully.";
    } else {
        echo "Failed to send email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Factuur</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/factuur.css">
    <style>
        @media print {
            .print-button,
            .email-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main class="main">
        <?php $counter = 1; ?>
        <?php foreach ($invoices as $invoice) { ?>
            <h1>Factuur <?php echo $counter; ?></h1>
            <ul>
                <li><strong>Username:</strong> <?php echo $invoice['username']; ?></li>
                <li><strong>Address:</strong> <?php echo $invoice['address']; ?></li>
                <li><strong>Email:</strong> <?php echo $invoice['email']; ?></li>
                <li><strong>Phone:</strong> <?php echo $invoice['phone']; ?></li>
                <li><strong>Room Type:</strong> <?php echo $invoice['room_type']; ?></li>
                <li><strong>Check-in Date:</strong> <?php echo $invoice['check_in_date']; ?></li>
                <li><strong>Check-out Date:</strong> <?php echo $invoice['check_out_date']; ?></li>
            </ul>
            <hr>
            <?php $counter++; ?>
        <?php } ?>

        <button class="print-button" onclick="window.print()">Print Factuur</button>

        <?php if (!isset($_POST['sendEmail'])) { ?>
            <div class="email-form">
                <form method="post" action="">
                    <input type="hidden" name="toEmail" value="<?php echo $invoices[0]['email']; ?>">
                    <input type="hidden" name="toName" value="<?php echo $invoices[0]['username']; ?>">
                    <input type="submit" name="sendEmail" class="print-button" value="Email Invoice">
                </form>
            </div>
        <?php } ?>
    </main>
</body>

</html>
