<?php
session_start();
if (!isset($_SESSION['voornaam'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Producten</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php  include '../../templates/navbar.php'; ?>
    </header>

    <main>
        <h1>Producten</h1>
    </main>
    <br>
    <main>
   
        <center>
        <article>
        <?php 
        try {
            $sql = "SELECT * FROM product";
            $stmt = $PDO->prepare($sql);
            $stmt->execute();
            $kolommen = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

                    $bgcolor = true;
                    $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
         <div class="formulier">
         <div class="formulier-text">
         <img src="data:image/png;base64,<?php echo $row['foto']; ?>" alt="Productfoto" width="30%" height="30%">
         <br> 
         <br>
        <form action="../../db/actions/productkopen.php" method="post">
       
        
        <br>
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="naam"><?php echo $row['naam']; ?></label>
        <input type="hidden" name="naam" value="<?php echo $row['naam']; ?>">
        <br>
        <br>
        <label for="prijs">Prijs: <?php echo $row['prijs']; ?>$</label>
        <input type="hidden" name="prijs" value="<?php echo $row['prijs']; ?>">
        <br>
        <input type="hidden" name="foto" value="<?php echo $row['foto']; ?>">
        <br>
        <input type="text" name="voorraad" placeholder="<?php echo $row['voorraad']; ?>" width="10%">
        <br>
        <br>       
        <!-- <input type="submit" name="submit" value="Wijzig gegevens"> -->
        <input type="submit" name="submit" value="Voeg toe aan winkelwagen">
        </div>
        </form>
        </div>
        <br>
            <?php  $bgcolor = ($bgcolor ? false : true);
        } ?>
        </article>
        </center>
        </main>
</body>

</html>
