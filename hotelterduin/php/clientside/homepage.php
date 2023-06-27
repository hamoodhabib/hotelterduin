<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Homepage</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/hp.css">

</head>

<body>
    <header>
        <?php  include '../../templates/navbar.php' ?>
    </header>

    <div class="homepage-container">
        <h1>Welcome to Hotel Ter Duin</h1>
        <p>Experience the epitome of luxury at Hotel Ter Duin. Nestled in a breathtaking location, our hotel offers an idyllic retreat for relaxation, rejuvenation, and memorable experiences. Immerse yourself in unparalleled comfort and impeccable service as you embark on a journey of indulgence.</p>
        <br>
        <center>
            <h1>Make a Reservation</h1>
                <p>Make your reservation now and enjoy the exotic rooms. <br> Click the images.</p>
        </center>
        <div class="room-boxes">
            <div class="room-box">
                <a href="products.php">
                    <img src="../../images/bed.png" alt="Regular Room">
                    <p>Regular Room</p>
                </a>
            </div>
            <div class="room-box">
                <a href="products.php">
                    <img src="../../images/regular.jpg" alt="Deluxe Room">
                    <p>Deluxe Room</p>
                </a>
            </div>
            <div class="room-box">
                <a href="products.php">
                    <img src="../../images/luxe.jpg" alt="Very Deluxe Room">
                    <p>Very Deluxe Room</p>
                </a>
            </div>
        </div>

        <div class="history-box">
            <h2>Our Rich History</h2>
            <p>Hotel Ter Duin boasts a rich heritage, dating back to its establishment in the early 20th century. Over the years, it has evolved into a premier destination for travelers seeking luxury, comfort, and exceptional service. Our hotel has been a favorite among discerning guests, offering an unforgettable experience that blends elegance, sophistication, and warm hospitality.</p>
        </div>

        <div class="job-box" onclick="window.location.href='contact.php'">
            <h2>Join Our Team - Job Opportunities</h2>
            <p>We are always looking for talented individuals to join our dedicated team. If you are passionate about hospitality, customer service, and creating memorable experiences, we invite you to explore our job opportunities. Click here to contact us and inquire about job openings.</p>
        </div>
    </div>
    <footer>
    <?php include '../../templates/footer.php'; ?> 
    </footer>
</body>

</html>
