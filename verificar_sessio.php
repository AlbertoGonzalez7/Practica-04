<?php
session_start();

// Duración máxima de la sesión en segundos (40 minutos)
$max_duracion_sesion = 5; // 40 minutos * 60 segundos

// Verificamos si el usuario está logueado y si el tiempo ha expirado
if (isset($_SESSION['usuari'])) {

    // Verificamos si el tiempo de inicio de la sesión está definido
    if (!isset($_SESSION['start_time'])) {
        $_SESSION['start_time'] = time(); // Inicializamos el tiempo si no está
    }

    // Calculamos el tiempo transcurrido desde el inicio de la sesión
    if (time() - $_SESSION['start_time'] > $max_duracion_sesion) {
        // Si ha pasado más de 40 minutos, destruimos la sesión y redirigimos   
        header("Location: Login/logout.php"); // Llamamos a logout.php para destruir la sesión y redirigir
        exit();
    } else {
        // Si la sesión aún es válida, actualizamos el tiempo de actividad
        $_SESSION['start_time'] = time();
    }

} else {
    // Si el usuario no está logueado, lo redirigimos a la página de login
    header("Location: index.php");
    exit();
}
?>