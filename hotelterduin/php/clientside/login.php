<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Redirect to home page or dashboard if the user is already logged in
    header('Location: ../../php/clientside/products.php');
    exit;
}

require_once dirname(__FILE__) . '/../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

if (isset($_POST['submit'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if the username exists in the Users table
    $stmt = $PDO->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['password']) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $username;

            // Redirect to appropriate page based on user type
            if ($user['role'] === 'customer') {
                header('Location: ../../php/clientside/homepage.php');
            } elseif ($user['role'] === 'employee') {
                header('Location: ../../php/clientside/homepage.php');
            } else {
                header('Location: ../../login.php'); // Redirect to the login page for non-customer and non-employee roles
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

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Log in</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main>
        <?php if (isset($error) && !empty($error)) : ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="formulier">
            <center>
                <h1>Log in</h1>
                <p>You need to register and log in to use the site, <br> this makes it easier. <br> Thank you.</p>
                <br>
                <form action="" method="post">
                    <div class="formulier-text">
                        <input type="text" name="username" placeholder="Username" required>
                        <br /><br />
                        <input type="password" name="password" placeholder="Password" required>
                        <br /><br />
                        <input type="submit" name="submit" value="Log in">
                    </div>
                </form>
                <br>
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </center>
        </div>
    </main>
</body>

</html>
