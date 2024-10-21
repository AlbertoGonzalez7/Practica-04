<?php
# Model per a les funcions relacionades amb els usuaris
require_once 'connexio.php';

function obtenirUsuariPerNom($usuari) {
    $connexio = connectarBD();
    $sql = "SELECT * FROM usuaris WHERE usuari = :usuari";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['usuari' => $usuari]);
    return $stmt->fetch(); // Retorna l'usuari si existeix
}

function inserirUsuari($usuari, $hashed_password) {
    $connexio = connectarBD();
    $sql = "INSERT INTO usuaris (usuari, contrasenya) VALUES (:usuari, :password)";
    $stmt = $connexio->prepare($sql);
    return $stmt->execute(['usuari' => $usuari, 'password' => $hashed_password]); // Retorna true si l'inserció és exitosa
}
?>
