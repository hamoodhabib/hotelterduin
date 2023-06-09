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
        // Check if the username already exists in the Login table
        $stmt = $PDO->prepare("SELECT * FROM Login WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $errors[] = 'Username already exists';
        } else {
            // Insert the new user into the Login table
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $PDO->prepare("INSERT INTO Login (username, password, user_type) VALUES (:username, :password, 'customer')");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();

            // Redirect to login page after successful registration
            header('Location: ../../php/clientside/login.php?success=Registration successful');
            exit;
        }
    }
}
?>