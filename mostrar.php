<?php 
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions
require_once "Database/connexio.php";
$connexio = new PDO("mysql:host=$db_host; dbname=$db_nom", $db_usuari, $db_password); 

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
$total_articles = $connexio->query('SELECT COUNT(*) FROM articles')->fetchColumn();
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
// la pàgina actual serà 4 - 1: 3, llavors s'ometen els primers 15 articles (3*5), i es mostren els 5 següents
$offset = ($pagina_actual - 1) * $articles_per_pagina;

// Obtindre els articles en la pàgina actual
$select = $connexio->prepare("SELECT * FROM articles LIMIT :offset, :articles_per_pagina");
// Asignem els valors d'offset i articles_per_pagines:
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
    <th>Titol</th>
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
// Generar la paginació amb punts suspensius
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
        // Si hi ha moltes pàgines, mostra el primer número de pàgina i l'últim,
        echo "<a href='?pagina=1&articles_per_pagina=$articles_per_pagina' class='" . ($pagina_actual == 1 ? "active" : "") . "'>1</a>";

        // Si la pàgina actual és major que 4, mostra punts suspensius
        if ($pagina_actual > 4) {
            echo "<span>...</span>";
        }

        // Mostra les pàgines depenent de la pagina actual, dues abans i dues després
        for ($i = max(2, $pagina_actual - 2); $i <= min($pagina_actual + 2, $total_pagines - 1); $i++) {
            // Si és la pàgina actual, color verd
            if ($i == $pagina_actual) {
                echo "<a class='active' href='?pagina=$i&articles_per_pagina=$articles_per_pagina'>$i</a>";
            } else {
                // Mostra les altres pàgines
                echo "<a href='?pagina=$i&articles_per_pagina=$articles_per_pagina'>$i</a>";
            }
        }

        // Si la pàgina actual està lluny del final, mostra punts suspensius
        if ($pagina_actual < $total_pagines - 3) {
            echo "<span>...</span>";
        }

        // Mostra l'última pàgina, color verd si es l'actual
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/estils.css">
    <title>Document</title>
</head>
<body>
    <p class="titol">Taula d'articles</p>
    
    <a href='index.php'>
        <button class="tornar" role="button">Anar enrere</button>
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

    <a href='Login/login.php'>
        <button class="login" role="button">Login/Sign up</button>
    </a>

</div>

</body> 
</html>
