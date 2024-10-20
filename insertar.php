<?php
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO

require_once "Database/connexio.php";
include 'verificar_sessio.php';

$connexio = new PDO("mysql:host=$db_host; dbname=$db_nom", $db_usuari, $db_password); 

$errors = [];
$titol = $cos = "";

// Verifiquem si s'ha enviat el formulari:
if (isset($_POST['insert'])) {
    $titol = trim($_POST['titol']);
    $cos = trim($_POST['cos']);

    // Verifiquem que cap dels dos camps estigui buit, si ho estan mostrem error:
    if (empty($titol)) {
        $errors[] = "El camp 'Títol' és obligatori.";
    }
    if (empty($cos)) {
        $errors[] = "El camp 'Cos' és obligatori.";
    }

    // Si hi ha errors, els guardem:
    if (!empty($errors)) {
        $_SESSION['missatge'] = implode("<br>", $errors); // Missatges d'error separats per un salt de línea
        $_SESSION['titol'] = $titol;
        $_SESSION['cos'] = $cos;
    } else {
        // Verifiquem si ja existeix l'article
        $select = $connexio->prepare('SELECT * FROM articles WHERE titol = ? AND cos = ?');
        $select->execute([$titol, $cos]);

        // Afegim les incidències:
        if ($select->rowCount() == 0) {
            function insert($connexio, $titol, $cos, $usuari_id) {
                $insert = $connexio->prepare("INSERT INTO articles(titol, cos, usuari_id) VALUES (?, ?, ?)");
                $insert->execute([$titol, $cos, $usuari_id]);
            }
            
            $usuari_id = $_SESSION['user_id']; 
            
            insert($connexio, $titol, $cos, $usuari_id);
            $_SESSION['missatge_exit'] = "Article insertat correctament"; // Missatge d'èxit
            $_SESSION['titol'] = ""; // Netejem el valor de titol
            $_SESSION['cos'] = ""; // Netejem el valor de cos
        } else {
            // Si ja existeix l'article
            $_SESSION['missatge'] = "L'article introduit ja existeix.";
            $_SESSION['titol'] = $titol;
            $_SESSION['cos'] = $cos;
        }
    }

    // Redirigim a la vista per mostrar el resultat (error o èxit)
    header("Location: Vistes/insertar.php");
    exit();
}

// Netejem les variables
if (!isset($_SESSION['titol'])) {
    $_SESSION['titol'] = "";
}
if (!isset($_SESSION['cos'])) {
    $_SESSION['cos'] = "";
}
?>
