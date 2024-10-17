<?php
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO
include 'verificar_sessio.php';
include 'Vistes/navbar_view.php';

if (isset($_SESSION['usuari'])) {
    $usuari = $_SESSION['usuari'];
    $user_id = $_SESSION['user_id'];  // Asegúrate de que el user_id se guarda en la sesión
} else {
    $usuari = "Invitat";
    $user_id = null;  // Si el usuario no está logueado, el ID será null
}

?>

<?php
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO

// Conexió per la base de dades:
require_once "Database/connexio.php";
$connexio = new PDO("mysql:host=$db_host; dbname=$db_nom", $db_usuari, $db_password);

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
        $_SESSION['id'] = $id;  // Guardem l'ID si es vàlid.
    }
}

// Si hi ha errors, els guardem i els mostrem a la vista:
if (!empty($errors)) {
    $_SESSION['missatge'] = implode("<br>", $errors);
    header("Location: Vistes/eliminar.php");
    exit();
}

// Si hi ha una búsqueda d'un article:
if (isset($_POST['buscar']) && $id) {
    $select = $connexio->prepare('SELECT * FROM articles WHERE id = ? AND usuari_id = ?');
    $select->execute([$id, $user_id]);

    if ($select->rowCount() > 0) {
        // Mostrem l'article
        $article = $select->fetch();
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
        echo "<form method='POST' action='eliminar.php'>
                <input type='hidden' name='id' value='{$article['ID']}' />
                <input type='submit' value='Eliminar' class='boto' name='eliminar'><br><br>
              </form>
                <a href='Vistes/eliminar.php'>
                <button class='tornar' role='button'>Anar enrere</button>
                </a>";
    } else {
        echo "<p class='titol'>L'article no ha sigut trobat</p>
                <a href='index_usuari.php'>
                <button class='tornar' role='button'>Anar enrere</button>
                </a>";
    }
}

// Si es fa clic en el botó d'eliminar
if (isset($_POST['eliminar']) && $id) {
    // Verificar que el artículo pertenece al usuario
    $checkOwnership = $connexio->prepare('SELECT * FROM articles WHERE id = ? AND usuari_id = ?');
    $checkOwnership->execute([$id, $user_id]);

    if ($checkOwnership->rowCount() > 0) {
        // Proceder a eliminar
        $del = $connexio->prepare('DELETE FROM articles WHERE id = ?');
        $del->execute([$id]);
        echo "<p class='titol'>Article eliminat correctament</p><br>";
        echo "<a href='index_usuari.php'>
              <button class='tornar' role='button'>Anar enrere</button>
              </a>";
    } else {
        echo "<p class='titol'>No pots eliminar aquest article, no ets el propietari.</p><br>";
        echo "<a href='index_usuari.php'>
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
    <link rel="stylesheet" type="text/css" href="CSS/estils.css">
    <title>Eliminar article</title>
</head>
<body>
</body>
</html>
