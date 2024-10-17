<?php

if (isset($_SESSION['usuari'])) {
    $usuari = $_SESSION['usuari'];
} else {
    $usuari = "Invitat";
}

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <!-- Texto de bienvenida -->
    <a class="navbar-brand">Benvingut <?php echo htmlspecialchars($usuari); ?></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      </ul>

      <!-- El formulario de logout alineado a la derecha -->
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="d-flex ms-auto" role="search">
        <input type="hidden" name="logout" value="1">
        <button class="btn btn-outline-success" type="submit">Logout</button>
      </form>

      <?php
      // Si se ha enviado el formulario de logout
      if (isset($_POST['logout'])) {
          // Redirigir dependiendo de la página actual
          $current_page = $_SERVER['SCRIPT_NAME']; // Obtener la ruta del archivo actual
          if (strpos($current_page, 'Vistes/') !== false) {
              // Si estamos en una página dentro de la carpeta 'Vistes'
              header('Location: ../Login/logout.php'); // Ajusta la ruta hacia atrás
          } else {
              // Para cualquier otra página
              header('Location: Login/logout.php');
          }
          exit; // Asegúrate de salir después de la redirección
      }
      ?>
    </div>
  </div>
</nav>

</body>
</html>
