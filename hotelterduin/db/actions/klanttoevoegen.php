<?php

session_start();

if (isset($_SESSION['user_id'])) {
    // Redirect to home page or dashboard if the user is already logged in
    header('Location: ../../php/clientside/products.php');
    exit;
}

require_once '../config.php';

$db = new Db();
$PDO = $db->getPDO();

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Validate input
    $errors = [];
    if (empty($username)) {
        $errors[] = 'Username is required';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match';
    }

    if (empty($errors)) {
        // Check if the username already exists in the users table
        $stmt = $PDO->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $errors[] = 'Username already exists';
        } else {
            // Insert the new user into the users table
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $PDO->prepare("INSERT INTO users (username, password, role, address, email, phone) VALUES (:username, :password, 'customer', :address, :email, :phone)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();

            // Redirect to login page after successful registration
            header('Location: ../../php/clientside/login.php?success=Registration successful');
            exit;
        }
    }
}
?>
