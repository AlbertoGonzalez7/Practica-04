<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

session_start();
include 'verificar_sessio.php';
include "Vistes/navbar_view.php";

if (isset($_SESSION['usuari'])) {
    $usuari = $_SESSION['usuari'];
} else {
    $usuari = "Invitat";
}

require_once "Database/connexio.php";
$connexio = new PDO("mysql:host=$db_host; dbname=$db_nom", $db_usuari, $db_password);

$errors = []; 
$exit = [];
$user_id = $_SESSION['user_id'] ?? null;

$id = trim($_POST['id'] ?? null);
$field = $_POST['field'] ?? null; // Pot ser titol o cos

// Verifiquem que l'ID no estigui buit.
if (empty($id)) {
    $errors[] = "El camp 'ID' és obligatori.";
    unset($_SESSION['id']);
} else {
    // Verifiquem que sigui un numero.
    if (!is_numeric($id)) {
        $errors[] = "El camp 'ID' no pot contenir lletres, només números.";
        unset($_SESSION['id']); // Eliminem el valor d'ID
    } else {
        $_SESSION['id'] = $id; // Guardem l'ID si es vàlid.
    }
}

// Si el camp new_value està buit, afegim error
if (isset($_POST['new_value']) && empty(trim($_POST['new_value']))) {
    if ($field === 'titol') {
        $errors[] = "El camp 'Títol' és obligatori."; // Missatge per títol
    } else if ($field === 'cos') {
        $errors[] = "El camp 'Cos' és obligatori."; // Missatge per cos
    }
}

// Si hi ha errors, els guardem i redirigim a vista.
if (!empty($errors)) {
    $_SESSION['missatge'] = implode("<br>", $errors);
    header("Location: Vistes/modificar.php");
    exit();
}

// Si no hi ha cap error, busquem l'article per modificar-lo
if ($id && $field) {
    $select = $connexio->prepare('SELECT * FROM articles WHERE id = ? AND usuari_id = ?');
    $select->execute([$id, $user_id]);
    unset($_SESSION['id']);

    if ($select->rowCount() > 0) {
        // Mostrar l'article
        $article = $select->fetch();
        echo "<p class='titol'>Article:</p>
        <div class='table-wrapper'>
                <table class='fl-table'>
                    <tr><th>ID</th><th>Títol</th><th>Cos</th></tr>
                    <tr>
                        <td>{$article['ID']}</td>
                        <td>{$article['titol']}</td>
                        <td>{$article['cos']}</td>
                    </tr>
                </table>
              </div>";

        // Si es fa click en el botó de modificar:
        if (isset($_POST['new_value'])) {
            $new_value = $_POST['new_value'];
            $update = $connexio->prepare("UPDATE articles SET $field = ? WHERE id = ? AND usuari_id = ?");
            $update->execute([$new_value, $id, $user_id]);

            $_SESSION['missatge_exit'] = "Article modificat correctament.";
            header("Location: Vistes/modificar.php");
            exit();
        } else {
            // Formulari per modificar títol o cos
            echo "<form method='POST' action='modificar.php' class='form-modificar'>
                    <input type='hidden' name='id' value='{$article['ID']}' />
                    <input type='hidden' name='field' value='{$field}' />
                    
                    <label class='titol-chulo' for='new_value'>Nou " . ($field === 'titol' ? 'Títol' : 'Cos') . " </label><br>
                    <textarea name='new_value' class='textarea'></textarea><br><br>
                    <button type='submit' class='boto'>Modificar</button>
                  </form>";

            // Botó per tornar enrere
            echo "<a href='index_usuari.php'>
                    <button class='tornar' role='button'>Anar enrere</button>
                  </a>";
        }
        // Si no es troba l'article:
    } else {
        $_SESSION['missatge'] = "L'article no ha sigut trobat.";
        unset($_SESSION['id']);
        header("Location: Vistes/modificar.php");
        exit();
    }
}
// Estils
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/estils.css">
    <title>Modificar article</title>
</head>
<body>

</body>
</html>
