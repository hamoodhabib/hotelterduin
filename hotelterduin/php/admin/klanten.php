<?php

session_start();

?>

<!DOCTYPE html>

<head>
    <title>Hamood</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php  include '../../templates/navbar.php' ?>
    </header>
    <br>
        <main>
        <article>
        <?php 
                    
        try {
            $sql = "SELECT * FROM klant";
            $stmt = $PDO->prepare($sql);
            $stmt->execute();
            $kolommen = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

                    $bgcolor = true;
                    $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  ?>
        <div class="formulier">
        <form action="../../db/actions/klantwijzigen.php" method="post">
        <div class="formulier-text">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="admin" value="<?php echo $row['admin']; ?>">
        <label for="voornaam">Naam:</label>
        <input type="text" name="voornaam" value="<?php echo $row['voornaam']; ?>">       
        <label for="password">Password:</label>
        <input type="text" name="password" value="<?php echo $row['password']; ?>">
        <label for="voornaam">Adres:</label>
        <input type="text" name="adres" value="<?php echo $row['adres']; ?>">
        <label for="voornaam">Postcode:</label>
        <input type="text" name="postcode" value="<?php echo $row['postcode']; ?>">
        <label for="voornaam">Woonplaats:</label>
        <input type="text" name="woonplaats" value="<?php echo $row['woonplaats']; ?>">
        <label for="voornaam">Email:</label>
        <input type="text" name="email" value="<?php echo $row['email']; ?>">
        <br/><br/>
        <input type="submit" name="submit" value="Wijzig gegevens"> 
        </div>
        </form>
                <br>
        <form action="../../db/actions/klantverwijderen.php" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <br><br/><br/><br/><br/><br/><br/>
                <input type="submit" name="submit" value="Verwijder klant">
            </div>
        </form>
        </div>
            <?php  $bgcolor = ($bgcolor ? false : true);
        } ?>
        </article>
<br>
        </main>
  </body>

</html>
