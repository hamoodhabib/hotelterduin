<?php

session_start();
require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

if (isset($_POST['submit'])) {
    // Update Reservation
    if ($_POST['submit'] === 'Update Reservation') {
        $reservation_id = $_POST['reservation_id'];
        $room_type = $_POST['room_type'];
        $check_in_date = $_POST['check_in_date'];
        $check_out_date = $_POST['check_out_date'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        try {
            $stmt = $PDO->prepare("UPDATE reservations SET room_type=:room_type, check_in_date=:check_in_date, check_out_date=:check_out_date, first_name=:first_name, last_name=:last_name, email=:email, phone=:phone WHERE id=:reservation_id");
            $stmt->bindParam(':room_type', $room_type);
            $stmt->bindParam(':check_in_date', $check_in_date);
            $stmt->bindParam(':check_out_date', $check_out_date);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':reservation_id', $reservation_id);
            $stmt->execute();

            header("Location: ../../php/admin/producten.php?success=Reservation successfully updated");
            exit();
        } catch (PDOException $e) {
            header("Location: ../../php/admin/producten.php?error=" . $e->getMessage());
            exit();
        }
    }

    // Delete Reservation
    if ($_POST['submit'] === 'Delete Reservation') {
        $reservation_id = $_POST['reservation_id'];

        try {
            $stmt = $PDO->prepare("DELETE FROM reservations WHERE id=:reservation_id");
            $stmt->bindParam(':reservation_id', $reservation_id);
            $stmt->execute();

            header("Location: ../../php/admin/producten.php?success=Reservation successfully deleted");
            exit();
        } catch (PDOException $e) {
            header("Location: ../../php/admin/producten.php?error=" . $e->getMessage());
            exit();
        }
    }
}
?>
