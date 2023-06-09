<?php

session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

try {
    $sql = "SELECT r.*, rt.room_type, u.username FROM reservations AS r INNER JOIN rooms AS rt ON r.room_id = rt.room_id INNER JOIN users AS u ON r.user_id = u.user_id";
    $stmt = $PDO->prepare($sql);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit']) && $_POST['submit'] === 'update') {
        $reservation_id = $_POST['reservation_id'];
        $room_type = $_POST['room_type'];
        $check_in_date = $_POST['check_in_date'];
        $check_out_date = $_POST['check_out_date'];

        try {
            $PDO->beginTransaction();

            // Retrieve the current room ID
            $currentRoomId = '';
            $currentRoomSql = "SELECT room_id FROM reservations WHERE reservation_id = :reservation_id";
            $currentRoomStmt = $PDO->prepare($currentRoomSql);
            $currentRoomStmt->bindParam(':reservation_id', $reservation_id);
            $currentRoomStmt->execute();
            $currentRoomData = $currentRoomStmt->fetch(PDO::FETCH_ASSOC);
            if ($currentRoomData) {
                $currentRoomId = $currentRoomData['room_id'];
            }
            $currentRoomStmt->closeCursor();

            // Retrieve the new room ID based on the selected room type
            $newRoomId = '';
            $newRoomSql = "SELECT room_id FROM rooms WHERE room_type = :room_type";
            $newRoomStmt = $PDO->prepare($newRoomSql);
            $newRoomStmt->bindParam(':room_type', $room_type);
            $newRoomStmt->execute();
            $newRoomData = $newRoomStmt->fetch(PDO::FETCH_ASSOC);
            if ($newRoomData) {
                $newRoomId = $newRoomData['room_id'];
            }
            $newRoomStmt->closeCursor();

            // Update the reservation with the new room ID and other details
            $updateSql = "UPDATE reservations SET room_id = :new_room_id, check_in_date = :check_in_date, check_out_date = :check_out_date WHERE reservation_id = :reservation_id";
            $updateStmt = $PDO->prepare($updateSql);
            $updateStmt->bindParam(':new_room_id', $newRoomId);
            $updateStmt->bindParam(':check_in_date', $check_in_date);
            $updateStmt->bindParam(':check_out_date', $check_out_date);
            $updateStmt->bindParam(':reservation_id', $reservation_id);
            $updateStmt->execute();
            $updateStmt->closeCursor();

            // Update the available count in the rooms table for the previous and new room
            $updateAvailableSql = "UPDATE rooms SET available = available + 1 WHERE room_id = :current_room_id";
            $updateAvailableStmt = $PDO->prepare($updateAvailableSql);
            $updateAvailableStmt->bindParam(':current_room_id', $currentRoomId);
            $updateAvailableStmt->execute();
            $updateAvailableStmt->closeCursor();

            $updateAvailableSql = "UPDATE rooms SET available = available - 1 WHERE room_id = :new_room_id";
            $updateAvailableStmt = $PDO->prepare($updateAvailableSql);
            $updateAvailableStmt->bindParam(':new_room_id', $newRoomId);
            $updateAvailableStmt->execute();
            $updateAvailableStmt->closeCursor();

            $PDO->commit();
        } catch (PDOException $e) {
            $PDO->rollBack();
            echo $e->getMessage();
        }
    } elseif (isset($_POST['submit']) && $_POST['submit'] === 'delete') {
        $reservation_id = $_POST['reservation_id'];

        try {
            $PDO->beginTransaction();

            // Retrieve the current room ID
            $currentRoomId = '';
            $currentRoomSql = "SELECT room_id FROM reservations WHERE reservation_id = :reservation_id";
            $currentRoomStmt = $PDO->prepare($currentRoomSql);
            $currentRoomStmt->bindParam(':reservation_id', $reservation_id);
            $currentRoomStmt->execute();
            $currentRoomData = $currentRoomStmt->fetch(PDO::FETCH_ASSOC);
            if ($currentRoomData) {
                $currentRoomId = $currentRoomData['room_id'];
            }
            $currentRoomStmt->closeCursor();

            // Delete the reservation
            $deleteSql = "DELETE FROM reservations WHERE reservation_id = :reservation_id";
            $deleteStmt = $PDO->prepare($deleteSql);
            $deleteStmt->bindParam(':reservation_id', $reservation_id);
            $deleteStmt->execute();
            $deleteStmt->closeCursor();

            // Update the available count in the rooms table
            $updateAvailableSql = "UPDATE rooms SET available = available + 1 WHERE room_id = :room_id";
            $updateAvailableStmt = $PDO->prepare($updateAvailableSql);
            $updateAvailableStmt->bindParam(':room_id', $currentRoomId);
            $updateAvailableStmt->execute();
            $updateAvailableStmt->closeCursor();

            $PDO->commit();
        } catch (PDOException $e) {
            $PDO->rollBack();
            echo $e->getMessage();
        }
    }
}

?>