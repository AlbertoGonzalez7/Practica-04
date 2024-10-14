<?php
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO
function connectarBD($db_host, $db_usuari, $db_password, $db_nom)
{
    try {
        $DB = new PDO("mysql:host=$db_host; dbname=$db_nom;charset=utf8", $db_usuari, $db_password);
        return $DB;
      } catch (PDOException $e) {
        echo "Error al conectarse a la base de dades: " + $e;
        die();
      } 
    
}
// Variables per la conexió a la BBDD
$db_host = "localhost";
$db_usuari = "root";
$db_password = "";
$db_nom = "pt04_alberto_gonzalez";

$db = connectarBD($db_host, $db_usuari, $db_password, $db_nom);