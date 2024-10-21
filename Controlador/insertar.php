<?php
# Alberto González Benítez, 2n DAW, Pràctica 04

require_once "../Model/connexio.php";  // Ajustado para subir un nivel a 'Model'
include 'verificar_sessio.php';      // Ajustado para subir un nivel
require_once "../Model/ArticlesModel.php";  // Ajustado para subir un nivel

// Obtenim la connexió a la base de dades
$connexio = connectarBD();

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
        $_SESSION['missatge'] = implode("<br>", $errors); // Missatges d'error separats per un salt de línia
        $_SESSION['titol'] = $titol;
        $_SESSION['cos'] = $cos;
    } else {
        // Verifiquem si ja existeix l'article utilitzant la funció del model
        if (!verificarArticle($titol, $cos, $connexio)) {
            // Inserim l'article si no existeix
            $usuari_id = $_SESSION['user_id'];  // Obtenim l'ID de l'usuari de la sessió
            if (inserirArticle($titol, $cos, $usuari_id, $connexio)) {
                $_SESSION['missatge_exit'] = "Article insertat correctament"; // Missatge d'èxit
                $_SESSION['titol'] = ""; // Netejem el valor de titol
                $_SESSION['cos'] = ""; // Netejem el valor de cos
            } else {
                $_SESSION['missatge'] = "Error en inserir l'article.";
            }
        } else {
            // Si ja existeix l'article
            $_SESSION['missatge'] = "L'article introduit ja existeix.";
            $_SESSION['titol'] = $titol;
            $_SESSION['cos'] = $cos;
        }
    }

    // Redirigim a la vista per mostrar el resultat (error o èxit)
    header("Location: ../Vistes/insertar.php"); // Ajustado para subir un nivel
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
