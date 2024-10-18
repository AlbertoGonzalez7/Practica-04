<?php

include '../verificar_sessio.php';
include "navbar_view.php";

if (isset($_SESSION['usuari'])) {
    $usuari = $_SESSION['usuari'];
} else {
    $usuari = "Invitat";
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" type="text/css" href="../CSS/estil_formulari.css">
</head>
<body>   
    <form method="POST" action="../insertar.php">
        <div class="form">
            <div class="title">Insertar article</div>
            <div class="subtitle">Afegeix el teu article! Títol i Cos</div>

            <div class="input-container ic2">
                <input name="titol" class="input" type="text" placeholder=" " value="<?php echo isset($_SESSION['titol']) ? htmlspecialchars($_SESSION['titol']) : ''; ?>" />
                <div class="cut"></div>
                <label for="titol" class="placeholder"></label>
            </div>
            <div class="input-container ic2">
                <input name="cos" class="input" type="text" placeholder=" " value="<?php echo isset($_SESSION['cos']) ? htmlspecialchars($_SESSION['cos']) : ''; ?>" />
                <div class="cut cut-short"></div>
                <label for="cos" class="placeholder"></label>
            </div>
            <br>
            <input type="submit" value="Insertar" class="insertar" name="insert">
            <br><br>

            <?php
            // Verifica si existeix un missatge d'èxit per mostrar:
            if (isset($_SESSION['missatge_exit'])) {
                echo "<p style='color: green; font-family: \"Calligraffitti\", cursive;'>" . ($_SESSION['missatge_exit']) . "</p>";
                unset($_SESSION['missatge_exit']); // Eliminar missatge després de mostrar-lo
            }

            // Verifica si existeix un missatge d'error per mostrar:
            else if (isset($_SESSION['missatge'])) {
                echo "<p style='color: red; font-family: \"Calligraffitti\", cursive;'>" . ($_SESSION['missatge']) . "</p>";
                unset($_SESSION['missatge']); // Eliminar missatge després de mostrar-lo
            }
            ?>

        </div>
    </form>

        <div>
            <a href="../index_usuari.php">
                <button type="button" class="tornar" role="button">Anar enrere</button>
            </a>
        </div>

</body>
</html>


