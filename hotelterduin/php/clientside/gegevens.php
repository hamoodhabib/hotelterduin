<?php

session_start();

require_once '../../db/config.php';


$db = new Db();
$PDO = $db->getPDO();

$ID = $_GET['id'];

// Query om de rij te selecteren die overeenkomt met het sessie-ID
$sql = "SELECT * FROM klant WHERE id = :id";
$stmt = $PDO->prepare($sql);
$stmt->execute(['id' => $ID]);
$klant = $stmt->fetch();


// Het ingevulde formulier verwerken
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $klant['id'];
    $voornaam = $_POST['voornaam'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $adres = $_POST['adres'];
    $postcode = $_POST['postcode'];
    $woonplaats = $_POST['woonplaats'];
    $email = $_POST['email'];

    // Query om de informatie van de gebruiker bij te werken
    $sql = "UPDATE klant SET voornaam = :voornaam, password = :password, adres = :adres, postcode = :postcode, woonplaats = :woonplaats, email = :email WHERE id = :id";
    $stmt = $PDO->prepare($sql);
    $stmt->execute(['voornaam' => $voornaam, 'password' => $password, 'adres' => $adres, 'postcode' => $postcode, 'woonplaats' => $woonplaats, 'email' => $email, 'id' => $id]);

    session_start();
    unset($_SESSION['voornaam']);
    session_destroy();
    header('Location: ../../php/clientside/login.php');
    // exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gegevens</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>
<body>
    <header>
        <?php include '../../templates/navbar.php' ?>
    </header>
    <main>
        <div class="formulier">
            <form action="#" method="POST">
                <div class="formulier-text">
                    <h2>Edit Account</h2>
                    <p>Username</p>
                    <input type="text" name="voornaam"value="<?php echo $klant['voornaam'] ?>" required>
                    <p>Password</p>
                    <input type="password" name="password" placeholder="*****" required>
                    <p>Address</p>
                    <input type="text" name="adres"value="<?php echo $klant['adres'] ?>" required>
                    <p>Postal Code</p>
                    <input type="text" name="postcode"value="<?php echo $klant['postcode'] ?>" required>
                    <p>City</p>
                    <input type="text" name="woonplaats"value="<?php echo $klant['woonplaats']?>" required>
                    <p>E-mail</p>
                    <input type="email" name="email"value="<?php echo $klant['email']?>" required>
                    <br><br><br>
                    <input type="submit" name="submit" value="Save Changes">
                </div>
            </form>
        </div>
    </main>
</body>
</html>
