<?php
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO

session_start();

if (isset($_SESSION['usuario'])) {
    $usuari = $_SESSION['usuari'];
} else {
    $usuari = "Invitat";
}

include "navbar_view.php";
include 'verificar_sessio.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/estil_formulari.css">
    <title>Eliminar article</title>
</head>
<body>   
<h2>
<form method="POST" action="../eliminar.php">

    <div class="form">
        <div class="title">Eliminar article</div>
        <div class="subtitle">Elimina tot l'article amb el seu ID</div>
        <div class="input-container ic1">
          <input name="id" class="input" type="text" placeholder=" " value="<?php echo isset($_SESSION['id']) ? htmlspecialchars($_SESSION['id']) : ''; ?>" />
          <div class="cut"></div>
          <label for="id" class="placeholder"></label>
        </div>
          <br>
          <input type="submit" value="Buscar" class="insertar" name="buscar">
          <br><br>
          <a href="../index_usuari.php">
              <button type="button" class="tornar" role="button">Anar enrere</button>
          </a>
          <?php
            // Verifica si existeix un missatge d'èxit per mostrar:
            if (isset($_SESSION['missatge_exit'])) {
                echo "<p style='color: green;'>" . ($_SESSION['missatge_exit']) . "</p>";
                unset($_SESSION['missatge_exit']); // Eliminar missatge després de mostrar-ho
            }

            // Verifica si existeix un missatge d'error per mostrar
            else if (isset($_SESSION['missatge'])) {
                echo "<p style='color: red;'>" . ($_SESSION['missatge']) . "</p>";
                unset($_SESSION['missatge']); // Eliminar missatge després de mostrar-ho
            }
            ?>
      </div>

</form>
</h2>
</body>
</html>
