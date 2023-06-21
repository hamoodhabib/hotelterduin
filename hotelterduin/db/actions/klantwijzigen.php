<?php

require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

if (isset($_POST['submit'])) {
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check the action type (update or delete)
    $action = $_POST['submit'];

    if ($action === 'Update') {
        // Update the user information
        $sql = "UPDATE users SET username=:username, address=:address, email=:email, phone=:phone WHERE user_id=:user_id";
        $stmt = $PDO->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':user_id', $userId);

        try {
            $stmt->execute();
            header("Location: ../../php/admin/klanten.php?success=Klantgegevens zijn gewijzigd");
            exit();
        } catch (PDOException $e) {
            echo $e->getMessage();
            header("Location: ../../php/admin/klanten.php?error=Unknown error occurred");
            exit();
        }
    } elseif ($action === 'Delete') {
        // Delete the user
        $sql = "DELETE FROM users WHERE user_id=:user_id";
        $stmt = $PDO->prepare($sql);
        $stmt->bindParam(':user_id', $userId);

        try {
            $stmt->execute();
            header("Location: ../../php/admin/klanten.php?success=Klantgegevens zijn verwijderd");
            exit();
        } catch (PDOException $e) {
            echo $e->getMessage();
            header("Location: ../../php/admin/klanten.php?error=Unknown error occurred");
            exit();
        }
    }
} else {
    header("Location: ../../php/admin/klanten.php");
    exit();
}
?>


