<?php
// session_start();

// include('../db/config.php');
require_once dirname(__FILE__) . '/../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

?>

<a class="logo" href="../clientside/homepage.php"><img src="../../images/logo.jpg" alt="logo" width="30%" height="60%"/></a>

<br>

<nav>
    
            
            <input id="hamburger" type="checkbox" />
            <label class="hamburger_btn" for="hamburger">
                <span></span>
            </label>
            <ul class="hamburger_menu">
                <li><a class="menu_item" href="../clientside/homepage.php"> Homepage</a></li>
                <li><a class="menu_item" href="../clientside/products.php"> Products</a></li>
                <li><a class="menu_item" href="../clientside/contact.php"> Contact</a></li>
                <?php
                if (isset($_SESSION['voornaam'])) {
                    ?>
                <li><div class="profile">
                    <input type="checkbox" name="profile" id="profile" />
                    <label for="profile">Profile</label>
                    <div class="pro">
                <p>
                    <?php echo $_SESSION['voornaam']; ?>
                    <br>
                    <?php 
                    if ($_SESSION['admin'] == 1) {?>
                    <div class="navbar">
                        <il class="menu">
                            <ol><a href="../admin/klanten.php">Klanten</a></ol>
                            <br><br>
                            <ol><a href="../admin/producten.php">Producten</a></ol>                            
                        </il>
                    </div>
                        <br>
                        <br>
                        <?php   
                    } else {
                        ?>
                    <a href="../clientside/gegevens.php?id=<?php echo $_SESSION['id'];?>">Gegevens</a>
                    <br>
                    <a href="../clientside/wagen.php?id=<?php echo $_SESSION['id'];?>">Winkelwagen</a>
                        <?php
                    }
                    ?>
                    <form action="../../db/actions/logout.php" method="post">
                        <input type="submit" name="submit" value="Logout">
                    </form>
                </p>
                <?php } else { ?>
                <li><a class="menu_item" href='../clientside/login.php'>Log in </a></li>
                <?php } 
                
                ?>
                    </div>
                    </div> 
                </li>
            </ul>
        </nav>
        
        <div id="breadcrumbs"></div>
