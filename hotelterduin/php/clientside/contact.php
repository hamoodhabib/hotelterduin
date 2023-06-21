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
                        <h1>Contact Us!</h1>
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="Your naam.." required><br><br>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Your email.." required><br><br>
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" placeholder="Your phonenumber.." required><br><br>
                        <label for="message">Questions?</label>
                        <textarea id="message" name="message" placeholder="Ask your question here.." style="height:50px" required></textarea><br><br>
                        <input type="submit" value="Submit">
                    </form>
                </center>  
            </div>
        </article>
    </main>

    <br><br>
    <footer>
    <?php include '../../templates/footer.php'; ?> 
    </footer>

</body>

</html>
