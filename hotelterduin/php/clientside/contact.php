<?php
session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id) {
    try {
        $stmt = $PDO->prepare("SELECT * FROM Contacts WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $userMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>
    <main>
        <article>
            <div class="formulier">
                <center>
                    <form method="post" action="../../db/actions/contactdb.php">
                        <h1>Contacteer ons!</h1>
                        <label for="name">Naam</label>
                        <input type="text" id="name" name="name" placeholder="Uw naam.." required><br><br>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Uw email.." required><br><br>
                        <label for="phone">Telefoon</label>
                        <input type="tel" id="phone" name="phone" placeholder="Uw telefoonnummer.." required><br><br>
                        <label for="message">Vragen?</label>
                        <textarea id="message" name="message" placeholder="Stel uw vraag hier.." style="height:200px" required></textarea><br><br>
                        <input type="submit" value="Submit">
                    </form>
                </center>  
            </div>
        </article>
    </main>

    <br><br>
    <footer>
        <div class="contact-info">
            <h2>Contact Informatie</h2>
            <p><strong>Hotel Address:</strong> Koningin Astrid Boulevard 5 -1, 2202 BK Noordwijk aan Zee, Nederland</p>
            <p><strong>Telephone:</strong> +31 12 34 56 78</p>
            <p><strong>Email:</strong> hotelterduin@hotmail.nl</p>
            <p><strong>Postal Address:</strong> 1234 XD Amsterdam</p>
        </div>
    </footer>

</body>

</html>
