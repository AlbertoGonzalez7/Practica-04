<?php
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO
session_start();

if (isset($_SESSION['usuari'])) {
    $usuari = $_SESSION['usuari'];
} else {
    $usuari = "Invitat";
}

include "Vistes/navbar_view.php";
include 'verificar_sessio.php';
?>

<?php 
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO
require_once "Database/connexio.php";

$connexio = new PDO("mysql:host=$db_host; dbname=$db_nom", $db_usuari, $db_password);


// Comprueba si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: Login/login.php"); // Redirigir a login si no está autenticado
    exit();
}

$usuario_id = $_SESSION['user_id']; // Obtener el ID del usuario de la sesión

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

// Obtenir el número total d'articles per aquest usuari
$total_articles = $connexio->prepare('SELECT COUNT(*) FROM articles WHERE usuari_id = :usuario_id');
$total_articles->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
$total_articles->execute();
$total_articles = $total_articles->fetchColumn();
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

// Es per calcular els articles per pàgines, si s'està en la pàgina 4:
$offset = ($pagina_actual - 1) * $articles_per_pagina;

// Obtindre els articles en la pàgina actual per aquest usuari
$select = $connexio->prepare("SELECT * FROM articles WHERE usuari_id = :usuario_id LIMIT :offset, :articles_per_pagina");
$select->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
$select->bindValue(':offset', $offset, PDO::PARAM_INT);
$select->bindValue(':articles_per_pagina', $articles_per_pagina, PDO::PARAM_INT);
// Executem la comanda.
$select->execute();
$resultats = $select->fetchAll();

// Funció per mostrar els articles
function mostrarTaula($resultats){
    echo "<div class='table-wrapper'>";
    echo "<table class='fl-table'>
    <tr>
    <th>ID</th>
    <th>Titol</th>
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

// Generar la paginació
function mostrarPaginacio($pagina_actual, $total_pagines, $articles_per_pagina) {
    echo "<div class='pagination'>";

    if ($pagina_actual > 1) {
        echo "<a href='?pagina=1&articles_per_pagina=$articles_per_pagina'>&laquo;</a>";  // Ir al inicio
    } else {
        echo "<a href='#' class='disabled'>&laquo;</a>";
    }

    if ($pagina_actual > 1) {
        echo "<a href='?pagina=" . ($pagina_actual - 1) . "&articles_per_pagina=$articles_per_pagina'>&lsaquo;</a>";  // Ir una página atrás
    } else {
        echo "<a href='#' class='disabled'>&lsaquo;</a>";
    }

    // Mostrar números de páginas
    if ($total_pagines <= 7) {
        for ($i = 1; $i <= $total_pagines; $i++) {
            if ($i == $pagina_actual) {
                echo "<a class='active' href='?pagina=$i&articles_per_pagina=$articles_per_pagina'>$i</a>"; // Cambiar el color a verde si es la página actual
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

    if ($pagina_actual < $total_pagines) {
        echo "<a href='?pagina=" . ($pagina_actual + 1) . "&articles_per_pagina=$articles_per_pagina'>&rsaquo;</a>";  // Ir una página adelante
    } else {
        echo "<a href='#' class='disabled'>&rsaquo;</a>";
    }

    if ($pagina_actual < $total_pagines) {
        echo "<a href='?pagina=$total_pagines&articles_per_pagina=$articles_per_pagina'>&raquo;</a>";  // Ir al final
    } else {
        echo "<a href='#' class='disabled'>&raquo;</a>";
    }

    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/estils.css">
    <title>Document</title>
</head>
<body>
    <p class="titol">Taula d'articles</p><br>
    
    <a href='index_usuari.php'>
        <button class="tornar" role="button">Anar enrere</button><br>
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
