<?php
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO
session_start();
// Conexió per la base de dades:
require_once "Database/connexio.php";
$connexio = new PDO("mysql:host=$db_host; dbname=$db_nom", $db_usuari, $db_password);

$errors = [];
$id = trim($_POST['id'] ?? null);

// Validació del camp ID, que no estigui buit, que no siguin lletres...
if (empty($id)) {
    $errors[] = "El camp 'ID' és obligatori.";
    unset($_SESSION['id']);
} else {

    if (!is_numeric($id)) {
        $errors[] = "El camp 'ID' no pot contenir lletres, només números.";
        unset($_SESSION['id']);  // Neteja el valor ID si no es valid
    } else {
        $_SESSION['id'] = $id;  // Guardem l'ID si es valid.
    }
}

// Si hi ha errors, els guardem i els mostrem a la vista:
if (!empty($errors)) {
    $_SESSION['missatge'] = implode("<br>", $errors);
    header("Location: Vistes/eliminar.php");  // Redirigim a la vista
    exit();
}

// Si es fa la busqueda d'un article:
if (isset($_POST['buscar']) && $id) {
    $select = $connexio->prepare('SELECT * FROM articles WHERE id = ?');
    $select->execute([$id]);

    if ($select->rowCount() > 0) {
        // Mostrem l'article
        $article = $select->fetch();
        echo "<div class='table-wrapper'>
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
        echo "<form method='POST' action='eliminar.php'>
                <input type='hidden' name='id' value='{$article['ID']}' />
                <input type='submit' value='Eliminar' class='tornar' name='eliminar'>
              </form>
                <a href='eliminar.php'>
                <button class='tornar' role='button'>Tornar enrere</button>
                </a>";

                // Si no ha sigut trobat l'article ho mostrem:
    } else {
        echo "<p class='titol'>L'article no ha sigut trobat</p>
                <a href='index.php'>
                <button class='tornar' role='button'>Tornar enrere</button>
                </a>";
    }
}

// Si es fa click en el botó d'eliminar
if (isset($_POST['eliminar']) && $id) {
    $del = $connexio->prepare('DELETE FROM articles WHERE id = ?');
    $del->execute([$id]);
    echo "<p class='titol'>Article eliminat correctament</p>";
    echo "<a href='index.php'>
          <button class='tornar' role='button'>Tornar enrere</button>
          </a>";
}
//Estils:
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/estils.css">
    <title>Eliminar article</title>
</head>
<body>
</body>
</html>
