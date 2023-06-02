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
</head>

<body>
    <header>
        <?php  include '../../templates/navbar.php' ?>
    </header>

    <main>
        <article>
        <div class="title">
            <center>
                <h1>Welkom bij de Futbol Shop</h1>
                <p>
                    Futbol Shop is een webshop waar je alles kan vinden voor de voetbal liefhebber. Van voetbalshirts tot voetbalschoenen en van voetbal accessoires tot voetbal tassen. Wij hebben het allemaal.
                </p>
                <h3>Zonder account kunt u niet bestellen!<br>Klik hieronder op de foto's om een account aan te maken.</h3>  
            </center>
            </div>
		</article>
		<br/><br/>
        <div class="slider">
            <div class="slide">
				<h1>Onze producten: Voetbalshirts - Klik de foto's voor meer</h1>
        		<a href="products.php"><img src="https://source.unsplash.com/random?soccer-player" alt="" /></a>
            </div>
            <div class="slide">
				<h1>Onze producten: Voetballen - Klik de foto's voor meer</h1>
                <a href="products.php"><img src="https://source.unsplash.com/random?soccer-team" alt="" /></a>
            </div>
            <div class="slide">
				<h1>Onze producten: Voetbalschoenen - Klik de foto's voor meer</h1>
                <a href="products.php"><img src="https://source.unsplash.com/random?soccer-shoes" alt="" /></a>
            </div>
        
            <button class="btn" id="btn-next">&#10596;</button>
            <button class="btn" id="btn-prev">&#10594;</button>
</main>
        </div>
        <br><br>
        <main>
            <section>
                <center>
                    <div class="search-bar">
                    <input type="text" id="zoektekst" placeholder="Zoek contacten"
                     onkeyup="zoekContacten(document.getElementById('zoektekst').value)">
                </div>
                <div id="grid">

                </div>
             </center>
            </section>
        </main>
        
        </main>

    
</body>

</html>
