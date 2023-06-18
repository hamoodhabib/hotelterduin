<?php

// session_start();

require_once dirname(__FILE__) . '/../config.php';

$db = new Db();
$PDO = $db->getPDO();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $roomType = $_POST['room_type'];
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Insert reservation into the database
    try {
        $stmt = $PDO->prepare("INSERT INTO reservations (room_type, check_in_date, check_out_date, first_name, last_name, email, phone)
                               VALUES (:room_type, :check_in_date, :check_out_date, :first_name, :last_name, :email, :phone)");
        $stmt->bindParam(':room_type', $roomType);
        $stmt->bindParam(':check_in_date', $checkInDate);
        $stmt->bindParam(':check_out_date', $checkOutDate);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        // Redirect to success page
        header('Location: thankyou.php');
        exit;
    } catch (PDOException $e) {
        // Display error message
        echo 'Error: ' . $e->getMessage();
    }
}

?>


<main>
            <div class="formulier">
                <h1>Make a Reservation</h1>
                <form method="post" action="">
                    <label for="room_type">Room Type:</label>
                    <select id="room_type" name="room_type">
                        <option value="single">Single Room</option>
                        <option value="double">Double Room</option>
                        <option value="suite">Suite</option>
                    </select>
                    <br /><br />
                    <label for="check_in_date">Check-in Date:</label>
                    <input type="date" id="check_in_date" name="check_in_date" required>
                    <br /><br />
                    <label for="check_out_date">Check-out Date:</label>
                    <input type="date" id="check_out_date" name="check_out_date" required>
                    <br /><br />
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                    <br /><br />
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                    <br /><br />
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <br /><br />
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                    <br /><br />
                    <input type="submit" value="Make Reservation">
                </form>
            </div>
        </main>
    </body>
