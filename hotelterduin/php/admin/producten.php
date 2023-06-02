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
            $sql = "SELECT * FROM product";
            $stmt = $PDO->prepare($sql);
            $stmt->execute();
            $kolommen = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

                    $bgcolor = true;
                    $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  ?>
        <h1><img src="data:image/png;base64,<?php echo $row['foto']; ?>" alt="Productfoto" width="50%" height="50%">  </h1>
        <br><br>    
        <div class="formulier">  
            
           

        <form method="post" enctype="multipart/form-data" action="../../db/actions/productwijzigen.php">
        <div class="formulier-text">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="naam">Naam:</label>
        <input type="text" name="naam" value="<?php echo $row['naam']; ?>" required>
        <br><br>
        <label for="prijs">Prijs:</label>
        <input type="number" name="prijs" value="<?php echo $row['prijs']; ?>" required>
        <br><br>
        <label for="foto">Foto:</label>
        <input type="file" name="foto" value="<?php echo $row['foto']; ?>"required>
        <br><br>
        <label for="voorraad">Voorraad:</label>
        <input type="number" name="voorraad" value="<?php echo $row['voorraad']; ?>" required>
        <br><br>
        <input type="submit" name="submit" value="Product Wijzigen">
    </form>

                <form action="../../db/actions/productverwijderen.php" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <br>
                <input type="submit" name="submit" value="Verwijder product">
            </div>
        </form>
        </div>
            <?php  $bgcolor = ($bgcolor ? false : true);
        } ?>
        </article>
    <br>
    <br>
    <article>
    <div class="formulier">
    <h2>Product toevoegen</h2>
    <br><br>
        <form method="post" enctype="multipart/form-data" action="../../db/actions/producttoevoegen.php">
        <div class="formulier-text">
        <label for="naam">Naam:</label>
        <input type="text" name="naam" required>
        <br>
        <label for="prijs">Prijs:</label>
        <input type="number" name="prijs" required>
        <br>
        <label for="foto">Foto:</label>
        <input type="file" name="foto" required>
        <br>
        <label for="voorraad">Voorraad:</label>
        <input type="number" name="voorraad" required>
        <br>
        <input type="submit" name="submit" value="Product Toevoegen">
    </form>
    </div>
    </div>
    </article>
        </main>
  </body>

</html>
