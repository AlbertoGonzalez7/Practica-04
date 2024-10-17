<?php

if (isset($_SESSION['usuari'])) {
    $usuari = $_SESSION['usuari'];
} else {
    $usuari = "Invitat";
}

include "Vistes/navbar_view.php";
include 'verificar_sessio.php';
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
            <p class="titol">Selecciona una opció</p>
            <br><br>

            <input type="submit" value="Insertar article" class="boto" name="insert" formaction="Vistes/insertar.php">
            <input type="submit" value="Mostrar articles" class="boto" name="select" formaction="mostrar_usuari.php">
            <input type="submit" value="Modificar article" class="boto" name="modificar" formaction="Vistes/modificar.php">
            <input type="submit" value="Eliminar article" class="boto" name="eliminar" formaction="Vistes/eliminar.php">
        </h2>
    </form>


</body>

</html>