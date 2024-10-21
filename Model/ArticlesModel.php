<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

//eliminar.php

// Funció per buscar un article per ID i usuari
function buscarArticlePerUsuari($id, $user_id, $connexio) {
    $select = $connexio->prepare('SELECT * FROM articles WHERE id = ? AND usuari_id = ?');
    $select->execute([$id, $user_id]);
    return $select->fetch();
}

// Funció per verificar si l'usuari és propietari de l'article
function verificarPropietatArticle($id, $user_id, $connexio) {
    $checkOwnership = $connexio->prepare('SELECT * FROM articles WHERE id = ? AND usuari_id = ?');
    $checkOwnership->execute([$id, $user_id]);
    return $checkOwnership->rowCount() > 0;
}

// Funció per eliminar un article
function eliminarArticle($id, $connexio) {
    $delete = $connexio->prepare('DELETE FROM articles WHERE id = ?');
    return $delete->execute([$id]);
}

// insertar.php

// Funció per verificar si l'article ja existeix
function verificarArticle($titol, $cos, $connexio) {
    $select = $connexio->prepare('SELECT * FROM articles WHERE titol = ? AND cos = ?');
    $select->execute([$titol, $cos]);
    return $select->rowCount() > 0;
}

// Funció per inserir un article
function inserirArticle($titol, $cos, $usuari_id, $connexio) {
    $insert = $connexio->prepare("INSERT INTO articles(titol, cos, usuari_id) VALUES (?, ?, ?)");
    return $insert->execute([$titol, $cos, $usuari_id]);
}

// Funció per verificar si un article existeix segons ID i usuari
function obtenirArticlePerIDiUsuari($id, $usuari_id, $connexio) {
    $select = $connexio->prepare('SELECT * FROM articles WHERE id = ? AND usuari_id = ?');
    $select->execute([$id, $usuari_id]);
    return $select->fetch(PDO::FETCH_ASSOC);
}

// Funció per actualitzar un camp d'un article
function modificarArticle($id, $usuari_id, $nou_valor, $camp, $connexio) {
    $update = $connexio->prepare("UPDATE articles SET $camp = ? WHERE id = ? AND usuari_id = ?");
    return $update->execute([$nou_valor, $id, $usuari_id]);
}

// modificar.php

function obtenirTotalArticlesUsuari($usuari_id, $connexio) {
    $stmt = $connexio->prepare('SELECT COUNT(*) FROM articles WHERE usuari_id = :usuari_id');
    $stmt->bindValue(':usuari_id', $usuari_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function obtenirArticlesPaginats($usuari_id, $offset, $articles_per_pagina, $connexio) {
    $stmt = $connexio->prepare("SELECT * FROM articles WHERE usuari_id = :usuari_id LIMIT :offset, :articles_per_pagina");
    $stmt->bindValue(':usuari_id', $usuari_id, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':articles_per_pagina', $articles_per_pagina, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// mostrar.php:

function obtenirTotalArticles($connexio) {
    // Obtener el número total de artículos
    $query = 'SELECT COUNT(*) FROM articles';
    $stmt = $connexio->query($query);
    return $stmt->fetchColumn();
}

// SU: senseUsuari

function obtenirArticlesPaginatsSU($offset, $articles_per_pagina, $connexio) {
    $stmt = $connexio->prepare("SELECT * FROM articles LIMIT :offset, :articles_per_pagina");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':articles_per_pagina', $articles_per_pagina, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

?>
