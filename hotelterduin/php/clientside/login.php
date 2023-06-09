<?php

session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

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
    <?php include '../../templates/navbar.php' ?>
</header>

<main>
    <?php if (isset($error)) : ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <div class="formulier">
        <center>
            <h1>Log in</h1>
            <br>
            <form action="../../db/actions/inlog.php" method="post">
                <div class="formulier-text">
                    <input type="text" name="username" placeholder="Username" required>
                    <br/><br/>
                    <input type="password" name="password" placeholder="Password" required>
                    <br/><br/>
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
