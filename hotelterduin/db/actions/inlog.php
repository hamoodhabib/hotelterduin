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
    $username = $_POST['username'] ?? ''; // Use the null coalescing operator to set a default value
    $password = $_POST['password'] ?? '';

    // Check if the username exists in the Login table
    $stmt = $PDO->prepare("SELECT * FROM Login WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $user['login_id'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['username'] = $username;

            // Redirect to appropriate page based on user type
            if ($user['user_type'] === 'customer') {
                header('Location: ../../php/clientside/homepage.php');
            } elseif ($user['user_type'] === 'employee') {
                header('Location: ../../php/clientside/homepage.php');
            }
            exit;
        } else {
            $error = 'Incorrect username or password';
        }
    } else {
        $error = 'Incorrect username or password';
    }
}
?>
