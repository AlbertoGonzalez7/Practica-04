<?php
session_start();
// Duración máxima de la sesión en segundos (40 minutos)
$max_duracion_sesion = 40 * 60; // 40 minutos * 60 segundos

// Verificamos si el usuario está logueado y si el tiempo ha expirado
if (isset($_SESSION['usuari'])) {

    // Verificamos si el tiempo de inicio de la sesión está definido
    if (!isset($_SESSION['start_time'])) {
        $_SESSION['start_time'] = time(); // Inicializamos el tiempo si no está
    }

    // Calculamos el tiempo transcurrido desde el inicio de la sesión
    if (time() - $_SESSION['start_time'] > $max_duracion_sesion) {
        // Si ha pasado más de 40 minutos, destruimos la sesión y redirigimos   

        $current_page = $_SERVER['SCRIPT_NAME']; // Obtener la ruta del archivo actual
        if (strpos($current_page, 'Vistes/') !== false) {
            // Si estamos en una página dentro de la carpeta 'Vistes'
            header('Location: ../Login/logout.php'); // Ajusta la ruta hacia atrás
        } else {
            // Para cualquier otra página
            header('Location: Login/logout.php');
        }
        exit;

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