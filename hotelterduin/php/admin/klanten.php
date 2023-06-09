<?php

session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Customers</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>
    <br>
    <main>
        <article>
            <?php
            try {
                $sql = "SELECT * FROM Login";
                $stmt = $PDO->prepare($sql);
                $stmt->execute();
                $logins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            foreach ($logins as $login) { ?>
                <div class="formulier">
                    <form action="../../db/actions/klantwijzigen.php" method="post">
                        <div class="formulier-text">
                            <input type="hidden" name="login_id" value="<?php echo $login['login_id']; ?>">
                            <input type="hidden" name="user_type" value="<?php echo $login['user_type']; ?>">
                            <label for="username">Username:</label>
                            <input type="text" name="username" value="<?php echo $login['username']; ?>">
                            <label for="password">Password:</label>
                            <input type="text" name="password" placeholder="<?php echo $login['password']; ?>" required>
                            <br /><br />
                            <input type="submit" name="submit" value="Wijzig gegevens">
                        </div>
                    </form>
                    <br>
                    <form action="../../db/actions/klantverwijderen.php" method="post">
                        <input type="hidden" name="login_id" value="<?php echo $login['login_id']; ?>">
                        <br><br /><br /><br /><br /><br /><br />
                        <input type="submit" name="submit" value="Verwijder klant">
                    </form>
                </div>
            <?php } ?>
        </article>
        <br>
    </main>
</body>

</html>
