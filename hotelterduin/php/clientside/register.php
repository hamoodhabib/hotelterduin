<?php

session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
<header>
    <?php include '../../templates/navbar.php' ?>
</header>

<main>
    <?php if (isset($errors) && !empty($errors)) : ?>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="formulier">
        <center>
            <h1>Register</h1>
            <br>
            <form action="../../db/actions/klanttoevoegen.php" method="post">
                <div class="formulier-text">
                    <input type="text" name="username" placeholder="Username" required>
                    <br/><br/>
                    <input type="password" name="password" placeholder="Password" required>
                    <br/><br/>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <br/><br/>
                    <input type="submit" name="submit" value="Register">
                </div>
            </form>
            <br>
            <p>Already have an account? <a href="login.php">Log in</a></p>
        </center>
    </div>
</main>
</body>
</html>
