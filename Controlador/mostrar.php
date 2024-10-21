<?php 
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions
require_once "../Model/ArticlesModel.php"; // Ajustado para subir un nivel a 'Model'
require_once "../Model/connexio.php";      // Ajustado para subir un nivel

// Obtenim la connexió a la base de dades
$connexio = connectarBD();

// Número d'articles per pàgines
$articles_per_pagina = isset($_GET['articles_per_pagina']) ? (int)$_GET['articles_per_pagina'] : 5;

// Comprova si hi ha una pàgina especificada, sino, 1:
if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
    $pagina_actual = (int) $_GET['pagina'];
} else {
    $pagina_actual = 1;
}

// Si intenten possar una pàgina que sigui més petita que 1, (com 0), redirigeix a la pàgina 1
if ($pagina_actual < 1) {
    header("Location: ?pagina=1");
    exit();
}

// Obtenir el número total d'articles
$total_articles = obtenirTotalArticles($connexio);
$total_pagines = ceil($total_articles / $articles_per_pagina);

// Si intenten possar una pàgina que sigui més gran al número total de pàgines, redirigeix a la pàgina 1
if ($pagina_actual > $total_pagines && $total_pagines > 0) {
    header("Location: ?pagina=1");
    exit();
}

// Si intenten possar lletres a la url, redirigeix a la pàgina 1
if (!isset($_GET['pagina']) || !is_numeric($_GET['pagina']) || $_GET['pagina'] < 1) {
    header("Location: ?pagina=1");
    exit();
}

// Es per calcular els articles per pàgines
$offset = ($pagina_actual - 1) * $articles_per_pagina;

// Obtindre els articles en la pàgina actual
$resultats = obtenirArticlesPaginatsSU($offset, $articles_per_pagina, $connexio);

// Funció per mostrar els articles
function mostrarTaula($resultats){
    echo "<div class='table-wrapper'>";
    echo "<table class='fl-table'>
    <tr>
    <th>Títol</th>
    <th>Cos</th>
    </tr>";

    foreach($resultats as $res) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($res['titol']) . "</td>";
        echo "<td>" . htmlspecialchars($res['cos']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
}

// Generar la paginació
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
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css"> <!-- Ajustado para subir un nivel -->
    <title>Taula d'articles</title>
</head>
<body>
    <p class="titol">Taula d'articles</p>
    
    <a href='../Login/login.php'>
            <button class="login" role="button">Login/Sign up</button>
        </a>

    <a href='../index.php'>
        <button class="tornar_mostrar" role="button">Anar enrere</button>
    </a>

    <br>
    
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

</body> 
</html>
