<?php

session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the form data
    $reservationId = $_POST['reservation_id'];

    if ($_POST['submit'] === 'Update Reservation') {
        $roomType = $_POST['room_type'];
        $checkInDate = $_POST['check_in_date'];

        // Retrieve the current room type
        $currentRoomType = '';
        try {
            $currentRoomTypeSql = "SELECT room_type FROM reservations AS r INNER JOIN rooms AS rt ON r.room_id = rt.room_id WHERE r.reservation_id = :reservation_id";
            $currentRoomTypeStmt = $PDO->prepare($currentRoomTypeSql);
            $currentRoomTypeStmt->bindParam(':reservation_id', $reservationId);
            $currentRoomTypeStmt->execute();
            $currentRoomType = $currentRoomTypeStmt->fetchColumn();
            $currentRoomTypeStmt->closeCursor(); // Close the previous result set
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Update the reservation in the database
        try {
            $PDO->beginTransaction();

            // Update the room type in the reservations table
            $updateReservationSql = "UPDATE reservations SET room_id = (SELECT room_id FROM rooms WHERE room_type = :room_type), check_in_date = :check_in_date WHERE reservation_id = :reservation_id";
            $updateReservationStmt = $PDO->prepare($updateReservationSql);
            $updateReservationStmt->bindParam(':room_type', $roomType);
            $updateReservationStmt->bindParam(':check_in_date', $checkInDate);
            $updateReservationStmt->bindParam(':reservation_id', $reservationId);
            $updateReservationStmt->execute();
            $updateReservationStmt->closeCursor(); // Close the previous result set

            // Update the available count in the rooms table for the current and new room types
            $updateRoomSql = "UPDATE rooms SET available = available + 1 WHERE room_type = :current_room_type; UPDATE rooms SET available = available - 1 WHERE room_type = :room_type";
            $updateRoomStmt = $PDO->prepare($updateRoomSql);
            $updateRoomStmt->bindParam(':current_room_type', $currentRoomType);
            $updateRoomStmt->bindParam(':room_type', $roomType);
            $updateRoomStmt->execute();
            $updateRoomStmt->closeCursor(); // Close the previous result set

            $PDO->commit();

            // Redirect or display a success message
            header("Location: ../../php/admin/reservaties.php");
            exit;
        } catch (PDOException $e) {
            $PDO->rollBack();
            echo "Error: " . $e->getMessage();
        }
    } elseif ($_POST['submit'] === 'Delete Reservation') {
        // Delete the reservation from the database
        try {
            $PDO->beginTransaction();

            // Retrieve the current room type
            $currentRoomType = '';
            $currentRoomTypeSql = "SELECT room_type FROM reservations AS r INNER JOIN rooms AS rt ON r.room_id = rt.room_id WHERE r.reservation_id = :reservation_id";
            $currentRoomTypeStmt = $PDO->prepare($currentRoomTypeSql);
            $currentRoomTypeStmt->bindParam(':reservation_id', $reservationId);
            $currentRoomTypeStmt->execute();
            $currentRoomType = $currentRoomTypeStmt->fetchColumn();
            $currentRoomTypeStmt->closeCursor(); // Close the previous result set

            // Update the available count in the rooms table for the current room type
            $updateRoomSql = "UPDATE rooms SET available = available + 1 WHERE room_type = :current_room_type";
            $updateRoomStmt = $PDO->prepare($updateRoomSql);
            $updateRoomStmt->bindParam(':current_room_type', $currentRoomType);
            $updateRoomStmt->execute();
            $updateRoomStmt->closeCursor(); // Close the previous result set

            // Delete the reservation
            $deleteSql = "DELETE FROM reservations WHERE reservation_id = :reservation_id";
            $deleteStmt = $PDO->prepare($deleteSql);
            $deleteStmt->bindParam(':reservation_id', $reservationId);
            $deleteStmt->execute();
            $deleteStmt->closeCursor(); // Close the previous result set

            $PDO->commit();

            // Redirect or display a success message
            header("Location: ../../php/admin/reservaties.php");
            exit;
        } catch (PDOException $e) {
            $PDO->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
