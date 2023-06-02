<?php
session_start();
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
        <?php  include '../../templates/navbar.php' ?>
    </header>

    <main>
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
    <input type="text" name="voornaam" placeholder="Username">
    <br/><br/>
    <input type="password" name="password" placeholder="Password">
    <br/><br/>
    <input type="submit" name="submit" value="Log-in">
    </div>
    </form>
    <br>
    <p>Geen account? <a href="register.php">Registreer</a></p>
    <br>
    <h3>Zonder account kunt u niet bestellen!</h3>    
    </main>


</center>
</div>

    
</body>

</html>
