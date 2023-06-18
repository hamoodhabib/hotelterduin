<!DOCTYPE html>
<html lang="en">

<head>
    <title>Navbar</title>
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <a class="logo" href="../clientside/homepage.php"><img src="../../images/logo.png" alt="logo"></a>
            <ul class="menu">
                <li><a class="menu_item <?php echo ($currentURL === '../clientside/homepage.php') ? 'current' : ''; ?>" href="../clientside/homepage.php">Homepage</a></li>
                <li><a class="menu_item <?php echo ($currentURL === '../clientside/products.php') ? 'current' : ''; ?>" href="../clientside/products.php">Rooms</a></li>
                <li><a class="menu_item <?php echo ($currentURL === '../clientside/contact.php') ? 'current' : ''; ?>" href="../clientside/contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== '') :
                    ?>
                    <li class="dropdown">
                        <a class="menu_item <?php echo ($currentURL === '#') ? 'current' : ''; ?>" href="#"><?php echo $_SESSION['username']?> &#9662;</a>
                        <ul class="dropdown-menu">
                            <?php if ($_SESSION['role'] == 'employee') : ?>
                                <li><a href="../admin/klanten.php">Klanten</a></li>
                                <li><a href="../admin/reservaties.php">Reservation</a></li>
                                <li><a href="../admin/rooms.php">Rooms</a></li>
                            <?php else : ?>
                                <li><a href="../clientside/gegevens.php?id=<?php echo $_SESSION['user_id'];?>">Gegevens</a></li>
                                <li><a href="../clientside/reservatie.php?id=<?php echo $_SESSION['user_id'];?>">Reservation</a></li>
                            <?php endif; ?>
                            <li>
                                <form action="../../db/actions/logout.php" method="post">
                                    <input type="submit" name="submit" value="Logout">
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else : ?>
                    <li><a class="menu_item <?php echo ($currentURL === '../clientside/login.php') ? 'current' : ''; ?>" href="../clientside/login.php">Log in</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div id="breadcrumbs"></div>

</body>

</html>
