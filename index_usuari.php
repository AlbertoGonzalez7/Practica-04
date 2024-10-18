<?php

include 'verificar_sessio.php';
include "Vistes/navbar_view.php";

// Verificar si el usuario está en sesión
if (isset($_SESSION['usuari'])) {
    $usuari = $_SESSION['usuari'];
} else {
    $usuari = "Invitat";
}

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

    <!-- Mostrar mensaje de alerta solo si la cookie está presente -->
    <?php if (isset($_COOKIE['login_exitos'])): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <strong> <?php echo htmlspecialchars($usuari); ?></strong>, t'has loguejat amb èxit
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <!-- Eliminar la cookie para que no vuelva a mostrar el mensaje -->
        <?php setcookie('login_exitos', '', time() - 3600, '/'); ?>
    <?php endif; ?>
    
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</html>
