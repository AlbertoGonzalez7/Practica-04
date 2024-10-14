<?php
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO
session_start();
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="utf-8" />
    <title>Modificar escollir</title>
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css">
</head>

<body>
    <form method="POST" action="../modificar.php">
        <h2>
            <p class="titol">Selecciona el camp a modificar</p> 
            
            <!-- Camp per introduir l'ID -->
            <div class="c-formContainer">
                <input type="text" class="boton-id" name="id" placeholder="ID" 
                       value="<?php echo isset($_SESSION['id']) ? htmlspecialchars($_SESSION['id']) : ''; ?>" />
            </div>
            <br>

            <!-- Switch per Títol -->
            <div class="checkbox-wrapper-22">
                <label class="switch" for="check-titol">
                    <input type="radio" id="check-titol" name="field" value="titol" required>
                    <div class="slider round"></div>
                </label>
                <span class="titol-chulo">Títol</span>
            </div>
            <br>

            <!-- Switch per Cos -->
            <div class="checkbox-wrapper-22">
                <label class="switch" for="check-cos">
                    <input type="radio" id="check-cos" name="field" value="cos" required>
                    <div class="slider round"></div>
                </label>
                <span class="titol-chulo">Cos</span>
            </div>
            <br>

            <input type="submit" value="Seleccionar" class="boto">

            <br><br>
            <a href="../index.php">
                <button type="button" class="tornar" role="button">Anar enrere</button>
            </a>

            <?php

            // Verifica si existeix un missatge d'èxit per mostrar:
            if (isset($_SESSION['missatge_exit'])) {
                echo "<p style='color: #2ee20e;'>" . htmlspecialchars($_SESSION['missatge_exit']) . "</p>"; // Missatge d'èixt.
                unset($_SESSION['missatge_exit']); // Eliminar missatge després de mostrar-lo
            }
            // Verifica si existeix un missatge d'error per mostrar:
            else if (isset($_SESSION['missatge'])) {
                echo "<p>" . htmlspecialchars($_SESSION['missatge']) . "</p>";
                unset($_SESSION['missatge']); // Eliminar missatge després de mostrar-lo
            }

            ?>

        </h2>
    </form>
</body>

</html>