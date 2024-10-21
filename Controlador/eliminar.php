<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

include 'verificar_sessio.php';  // Cambiado para subir un nivel
include '../Vistes/navbar_view.php'; // Cambiado para subir un nivel
require_once "../Model/ArticlesModel.php";
require_once "../Model/connexio.php";

// Obtenim l'usuari de la sessió
if (isset($_SESSION['usuari'])) {
    $usuari = $_SESSION['usuari'];
    $user_id = $_SESSION['user_id'];  // Assegurar que el user_id es guarda a la sessió
} else {
    $usuari = "Invitat";
    $user_id = null;  // Si el usuari no està loguejat, el ID serà null
}

// Conexió per la base de dades:
$connexio = connectarBD();

$errors = [];
$id = trim($_POST['id'] ?? null);

// Validació del camp ID
if (empty($id)) {
    $errors[] = "El camp 'ID' és obligatori.";
    unset($_SESSION['id']);
} else {
    if (!is_numeric($id)) {
        $errors[] = "El camp 'ID' no pot contenir lletres, només números.";
        unset($_SESSION['id']);
    } else {
        $_SESSION['id'] = $id;  // Guardem l'ID si és vàlid.
    }
}

// Si hi ha errors, els guardem i els mostrem a la vista:
if (!empty($errors)) {
    $_SESSION['missatge'] = implode("<br>", $errors);
    header("Location: ../Vistes/eliminar.php"); // Cambiado para subir un nivel
    exit();
}

// Si hi ha una búsqueda d'un article:
if (isset($_POST['buscar']) && $id) {
    $article = buscarArticlePerUsuari($id, $user_id, $connexio);

    if ($article) {
        // Mostrem l'article
        echo "<p class='titol'>Article:</p>";
        echo "<br><br><br><br><div class='table-wrapper'>
                <table class='fl-table'>
                    <tr><th>ID</th><th>Títol</th><th>Cos</th></tr>
                    <tr>
                        <td>{$article['ID']}</td>
                        <td>{$article['titol']}</td>
                        <td>{$article['cos']}</td>
                    </tr>
                </table>
              </div>";

        // Botons per eliminar o tornar enrere
        echo "<form method='POST' action='modificar.php'>  <!-- Cambiado a modificar.php -->
                <input type='hidden' name='id' value='{$article['ID']}' />
                <input type='submit' value='Eliminar' class='boto' name='eliminar'><br><br>
              </form>
              <a href='../Vistes/eliminar.php'>
                <button class='tornar' role='button'>Anar enrere</button>
              </a>";
    } else {
        echo "<p class='titol'>L'article no ha sigut trobat</p>
              <a href='../Vistes/index_usuari.php'>
                <button class='tornar' role='button'>Anar enrere</button>
              </a>";
    }
}

// Si es fa clic en el botó d'eliminar
if (isset($_POST['eliminar']) && $id) {
    if (verificarPropietatArticle($id, $user_id, $connexio)) {
        if (eliminarArticle($id, $connexio)) {
            echo "<p class='titol'>Article eliminat correctament</p><br>";
            echo "<a href='../Vistes/index_usuari.php'>
                  <button class='tornar' role='button'>Anar enrere</button>
                  </a>";
        }
    } else {
        echo "<p class='titol'>No pots eliminar aquest article, no ets el propietari.</p><br>";
        echo "<a href='../Vistes/index_usuari.php'>
              <button class='tornar' role='button'>Anar enrere</button>
              </a>";
    }
}
// Estils:
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css"> <!-- Cambiado para subir un nivel -->
    <title>Eliminar article</title>
</head>
<body>
</body>
</html>
