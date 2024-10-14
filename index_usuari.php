<?php
session_start();

if (isset($_SESSION['usuario'])) {
    $usuari = $_SESSION['usuario'];
} else {
    $usuari = "Invitat";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Pràctica 2 - Connexions PDO</title>
    <link rel="stylesheet" type="text/css" href="CSS/estils.css">
</head>

<body>
    <form method="POST" action="../Database/connexio.php">
        <h2>
            <p class="titol">Selecciona una opció</p>

            <input type="submit" value="Insertar article" class="boto" name="insert" formaction="Vistes/insertar.php">
            <input type="submit" value="Mostrar articles" class="boto" name="select" formaction="mostrar.php">
            <input type="submit" value="Modificar article" class="boto" name="modificar" formaction="Vistes/modificar.php">
            <input type="submit" value="Eliminar article" class="boto" name="eliminar" formaction="Vistes/eliminar.php">
        </h2>
    </form>

    <a href='index.php'>
        <button class="logout" role="button">Logout</button>
    </a>

    <button class="benvingut">Benvingut <?php echo htmlspecialchars($usuari); ?></button>


</body>

</html>