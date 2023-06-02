<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelwagen</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<header>
    <?php  include '../../templates/navbar.php'; ?>
</header>
<body>
<div class="formulier"> 
    <main>
    <?php 
    try {
        $sql = "SELECT * FROM winkelwagen";
        $stmt = $PDO->prepare($sql);
        $stmt->execute();
        $kolommen = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

                $bgcolor = true;
                $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        echo "
        <center>
        <div class='formulier-text'>
       <input type='hidden' name='id' value='$row[id]'>
       <h5>$row[naam]</h5>
       <p>$ " . $row['prijs'] * $row['voorraad'] . " </p> 
       <input type='voorraad'naam='voorraad' value='$row[voorraad]'>
       <a href='../../db/actions/verwijderwinkelwagen.php? id=$row[id]'><button>Delete Product</button></a>
       <br/><br/>
       </div>
        </center>
        ";
        $bgcolor = ($bgcolor ? false : true);
    }
        
    ?>
    
    </main>
    </div>
</div>
<br/>
<div class="formulier">
<form method="post" action="../../db/actions/bestel.php">
    <button type="submit">Bestel</button>
</form>
<br/>
    <a href="products.php">
            <button>Back</button>
    </a>
    </div>
    </div>
</body>
</html>
