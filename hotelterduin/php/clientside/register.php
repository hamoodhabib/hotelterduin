<?php

session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registreer</title>
    <link rel="stylesheet" href="../../css/register.css">
    <link rel="stylesheet" href="../../css/navbar.css">

    <script src="../js/homepage.js" defer></script>
</head>

<body>
<header>
        <?php  include '../../templates/navbar.php' ?>
    </header>
    <main>
    <div class="formulier">
        <form action="../../db/actions/klanttoevoegen.php" method="POST">
            <div class="formulier-text">
                <h2>Create an account</h2>
                <p>Username</p>
                <input type="text" name="voornaam" required>
                <p>Password</p>
                <input type="password" name="password" required>
                <p>Address</p>
                <input type="text" name="adres" required>
                <p>Postal Code</p>
                <input type="text" name="postcode" required>
                <p>City</p>
                <input type="text" name="woonplaats" required>
                <p>E-mail</p>
                <input type="email" name="email" required>
                <br>
                <br>
                <input type="submit" name="submit" value="Create Account">
                
            </div>
        </form>
    </div>
    </main>
</body>

</html>
