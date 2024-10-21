<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

include 'verificar_sessio.php';
include "../Vistes/navbar_view.php";
require_once "../Model/ArticlesModel.php"; // Incluir el modelo de artículos
require_once "../Model/connexio.php";

// Obtenim la connexió a la base de dades
$connexio = connectarBD();

// Comprobar que estigui loguejat l'usuari
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php"); 
    exit();
}

$usuari_id = $_SESSION['user_id']; // ID de l'usuari
$articles_per_pagina = isset($_GET['articles_per_pagina']) ? (int)$_GET['articles_per_pagina'] : 5;
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($pagina_actual < 1) {
    header("Location: ?pagina=1");
    exit();
}

// Obtenir el número total d'articles per aquest usuari
$total_articles = obtenirTotalArticlesUsuari($usuari_id, $connexio);
$total_pagines = ceil($total_articles / $articles_per_pagina);

if ($pagina_actual > $total_pagines && $total_pagines > 0) {
    header("Location: ?pagina=1");
    exit();
}

if (!isset($_GET['pagina']) || !is_numeric($_GET['pagina']) || $_GET['pagina'] < 1) {
    header("Location: ?pagina=1");
    exit();
}

$offset = ($pagina_actual - 1) * $articles_per_pagina;

// Obtindre els articles en la pàgina actual per aquest usuari
$resultats = obtenirArticlesPaginats($usuari_id, $offset, $articles_per_pagina, $connexio);

// Funció per mostrar els articles
function mostrarTaula($resultats){
    echo "<div class='table-wrapper'>";
    echo "<table class='fl-table'>
    <tr>
    <th>ID</th>
    <th>Títol</th>
    <th>Cos</th>
    </tr>";

    foreach($resultats as $res) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($res['ID']) . "</td>";
        echo "<td>" . htmlspecialchars($res['titol']) . "</td>";
        echo "<td>" . htmlspecialchars($res['cos']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
}

// Funció per generar la paginació
function mostrarPaginacio($pagina_actual, $total_pagines, $articles_per_pagina) {
    echo "<div class='pagination'>";

    // Botó per anar a la primera pàgina
    if ($pagina_actual > 1) {
        echo "<a href='?pagina=1&articles_per_pagina=$articles_per_pagina'>&laquo;</a>";
    } else {
        echo "<a href='#' class='disabled'>&laquo;</a>";
    }

    // Botó per anar a la pàgina anterior
    if ($pagina_actual > 1) {
        echo "<a href='?pagina=" . ($pagina_actual - 1) . "&articles_per_pagina=$articles_per_pagina'>&lsaquo;</a>";
    } else {
        echo "<a href='#' class='disabled'>&lsaquo;</a>";
    }

    // Mostra el número de pàgines
    if ($total_pagines <= 7) {
        for ($i = 1; $i <= $total_pagines; $i++) {
            if ($i == $pagina_actual) {
                echo "<a class='active' href='?pagina=$i&articles_per_pagina=$articles_per_pagina'>$i</a>";
            } else {
                echo "<a href='?pagina=$i&articles_per_pagina=$articles_per_pagina'>$i</a>";
            }
        }
    } else {
        echo "<a href='?pagina=1&articles_per_pagina=$articles_per_pagina' class='" . ($pagina_actual == 1 ? "active" : "") . "'>1</a>";

        if ($pagina_actual > 4) {
            echo "<span>...</span>";
        }

        for ($i = max(2, $pagina_actual - 2); $i <= min($pagina_actual + 2, $total_pagines - 1); $i++) {
            if ($i == $pagina_actual) {
                echo "<a class='active' href='?pagina=$i&articles_per_pagina=$articles_per_pagina'>$i</a>";
            } else {
                echo "<a href='?pagina=$i&articles_per_pagina=$articles_per_pagina'>$i</a>";
            }
        }

        if ($pagina_actual < $total_pagines - 3) {
            echo "<span>...</span>";
        }

        if ($pagina_actual != $total_pagines) {
            echo "<a href='?pagina=$total_pagines&articles_per_pagina=$articles_per_pagina'>$total_pagines</a>";
        } else {
            echo "<a class='active' href='?pagina=$total_pagines&articles_per_pagina=$articles_per_pagina'>$total_pagines</a>";
        }
    }

    // Botó per anar a la pàgina següent
    if ($pagina_actual < $total_pagines) {
        echo "<a href='?pagina=" . ($pagina_actual + 1) . "&articles_per_pagina=$articles_per_pagina'>&rsaquo;</a>";
    } else {
        echo "<a href='#' class='disabled'>&rsaquo;</a>";
    }

    // Botó per anar a l'última pàgina
    if ($pagina_actual < $total_pagines) {
        echo "<a href='?pagina=$total_pagines&articles_per_pagina=$articles_per_pagina'>&raquo;</a>";
    } else {
        echo "<a href='#' class='disabled'>&raquo;</a>";
    }

    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css">
    <title>Taula d'articles</title>
</head>
<body>
    <p class="titol">Taula d'articles</p><br>

    <div class="articulos">
        <h2>
            <?php mostrarTaula($resultats); ?>
        </h2>
    </div>

    <!-- Paginació -->
    <?php mostrarPaginacio($pagina_actual, $total_pagines, $articles_per_pagina); ?>

    <div class="box">
        <select id="articles" onchange="location = this.value;">
            <option value="?pagina=1&articles_per_pagina=5" <?php echo (isset($_GET['articles_per_pagina']) && $_GET['articles_per_pagina'] == 5) ? 'selected' : ''; ?>>5 articles</option>
            <option value="?pagina=1&articles_per_pagina=10" <?php echo (isset($_GET['articles_per_pagina']) && $_GET['articles_per_pagina'] == 10) ? 'selected' : ''; ?>>10 articles</option>
            <option value="?pagina=1&articles_per_pagina=15" <?php echo (isset($_GET['articles_per_pagina']) && $_GET['articles_per_pagina'] == 15) ? 'selected' : ''; ?>>15 articles</option>
        </select>
    </div>

    <div>
        <a href="../Vistes/index_usuari.php">
            <button type="button" class="tornar" role="button">Anar enrere</button>
        </a>
    </div>
    
</body>
</html>
