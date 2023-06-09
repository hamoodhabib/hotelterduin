<?php
// session_start();

require_once dirname(__FILE__) . '/../db/config.php';

$db = new Db();
$PDO = $db->getPDO();
?>

<a class="logo" href="../clientside/homepage.php"><img src="../../images/logo.jpg" alt="logo" width="30%" height="60%"/></a>

<br>

<nav>
    <ul class="menu">
        <li><a class="menu_item" href="../clientside/homepage.php">Homepage</a></li>
        <li><a class="menu_item" href="../clientside/products.php">Reservation</a></li>
        <li><a class="menu_item" href="../clientside/contact.php">Contact</a></li>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== '') : ?>
            <li>
                <div class="profile">
                    <input type="checkbox" name="profile" id="profile" />
                    <label for="profile">Profile</label>
                    <div class="pro">
                        <p>
                            <?php echo $_SESSION['username']; ?>
                            <br>

                            <?php if ($_SESSION['user_type'] == 'employee') : ?>
                                <div class="navbar">
                                    <ul class="menu">
                                        <li><a href="../admin/klanten.php">Klanten</a></li>
                                        <br><br>
                                        <li><a href="../admin/producten.php">Reservations</a></li>                            
                                    </ul>
                                </div>
                                <br>
                                <br>
                            <?php else : ?>
                                <a href="../clientside/gegevens.php?id=<?php echo $_SESSION['user_id'];?>">Gegevens</a>
                                <br>
                                <a href="../clientside/wagen.php?id=<?php echo $_SESSION['user_id'];?>">Winkelwagen</a>
                            <?php endif; ?>

                            <form action="../../db/actions/logout.php" method="post">
                                <input type="submit" name="submit" value="Logout">
                            </form>
                        </p>
                    </div>
                </div> 
            </li>
        <?php else : ?>
            <li><a class="menu_item" href="../clientside/login.php">Log in</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div id="breadcrumbs"></div>
