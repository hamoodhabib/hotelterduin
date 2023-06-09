<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
<header>
    <?php  include '../../templates/navbar.php' ?>
</header>
<main>
    <article>
        <div class="formulier">
            <center>
                <form method="post" action="../../db/actions/contactdb.php">
                    <h1>Contacteer ons!</h1>
                    <label for="name">Naam</label>
                    <input type="text" id="name" name="name" placeholder="Uw naam.." required><br><br>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Uw email.." required><br><br>
                    <label for="phone">Telefoon</label>
                    <input type="tel" id="phone" name="phone" placeholder="Uw telefoonnummer.." required><br><br>
                    <label for="message">Vragen?</label>
                    <textarea id="message" name="message" placeholder="Stel uw vraag hier.." style="height:200px" required></textarea><br><br>
                    <input type="submit" value="Submit">
                </form>
            </center>  
        </div>
    </article>
    <br><br>
    <div class="formulier">
        <center>
            <h2>Mensen vragen ook:</h2>
            <div class="vraag">
                <input type="checkbox" name="vraag1" id="vraag1" />
                <label for="vraag1">Vraag 1: </label>
                <div class="antwoord">
                    <p>Lorem ipsum dolor sit amet consectetur adipsicing elit. Cumque nobis dolores ipsum expedita
                        nisi ratione
                    </p>
                </div>
            </div>
            <div class="vraag">
                <input type="checkbox" name="vraag2" id="vraag2" />
                <label for="vraag2">Vraag 2</label>
                <div class="antwoord">
                    <p>Lorem ipsum dolor sit amet consectetur adipsicing elit. Cumque nobis dolores ipsum expedita
                        nisi ratione
                    </p>
                </div>
            </div>
            <div class="vraag">
                <input type="checkbox" name="vraag3" id="vraag3" />
                <label for="vraag3">Vraag 3</label>
                <div class="antwoord">
                    <p>Lorem ipsum dolor sit amet consectetur adipsicing elit. Cumque nobis dolores ipsum expedita
                        nisi ratione
                    </p>
                </div>
            </div>
        </center>
    </div>
</main>
</body>

</html>
