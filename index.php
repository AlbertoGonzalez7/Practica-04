<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

include "Login/missatge_logout.php";

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Pràctica 4</title>
    <link rel="stylesheet" type="text/css" href="CSS/estils.css">
</head>

<body>
    <form method="POST" action="../Database/connexio.php">
        <h2>
            <p class="titol">Selecciona una opció</p><br>

            <input type="submit" value="Mostrar articles" class="boto" name="select" formaction="Controlador/mostrar.php">
        </h2>
    </form>

    <a href='Login/login.php'>
        <button class="login" role="button">Login/Sign up</button>
    </a>


</body>


</html>