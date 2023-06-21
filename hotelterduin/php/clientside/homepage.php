<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Homepage</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <script src="../../js/homepage.js" defer></script>
    <style>
        .homepage-container {
            text-align: center;
            margin-top: 50px;
        }

        .homepage-container h2 {
            color: black;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .room-boxes {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }

        .room-box {
            width: 200px;
            height: 300px;
            background-color: lightseagreen;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0 10px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .room-box:hover {
            background-color: darkcyan;
        }

        .room-box img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .room-box p {
            font-size: 18px;
            font-weight: bold;
        }

        .room-box a {
            color: #fff;
            text-decoration: none;
        }

        .history-box {
            width: 500px;
            margin: 50px auto;
            border: 2px solid darkcyan;
            padding: 20px;
            text-align: center;
            font-size: 16px;
        }

        .job-box {
            width: 500px;
            margin: 50px auto;
            border: 2px solid darkcyan;
            padding: 20px;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .job-box:hover {
            background-color: lightcyan;
        }
    </style>
</head>

<body>
    <header>
        <?php  include '../../templates/navbar.php' ?>
    </header>

    <div class="homepage-container">
        <h2>Welcome to Hotel Ter Duin</h2>
        <p>Experience the epitome of luxury at Hotel Ter Duin. Nestled in a breathtaking location, our hotel offers an idyllic retreat for relaxation, rejuvenation, and memorable experiences. Immerse yourself in unparalleled comfort and impeccable service as you embark on a journey of indulgence.</p>

        <div class="room-boxes">
            <div class="room-box">
                <a href="products.php">
                    <img src="../../images/logo.png" alt="Regular Room">
                    <p>Regular Room</p>
                </a>
            </div>
            <div class="room-box">
                <a href="products.php">
                    <img src="../../images/logo.png" alt="Deluxe Room">
                    <p>Deluxe Room</p>
                </a>
            </div>
            <div class="room-box">
                <a href="products.php">
                    <img src="../../images/logo.png" alt="Very Deluxe Room">
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
