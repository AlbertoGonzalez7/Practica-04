<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

session_start();

// Duració màxima de la sessió en segons (40 minuts)
$max_duracio_sessio = 40 * 60;

// Verifiquem si l'usuari està loguejat i si el temps ha expirat
if (isset($_SESSION['usuari'])) {

    // Verifiquem si el temps d'inici de la sessió està definit
    if (!isset($_SESSION['start_time'])) {
        $_SESSION['start_time'] = time(); // Inicialitzem el temps si no ho està
    }


    // Calculem el temps transcorregut des de l'inici de la sessió
    if (time() - $_SESSION['start_time'] > $max_duracio_sessio) {

        // Si han passat més de 40 minuts, destruïm la sessió i redirigim
        $current_page = $_SERVER['SCRIPT_NAME'];
        if (strpos($current_page, 'Vistes/') !== false) {
            // Si estem en una pàgina dins la carpeta 'Vistes'
            header('Location: ../Login/logout.php');
        } else {
            // Per a qualsevol altra pàgina
            header('Location: Login/logout.php');
        }
        exit;

    } else {
        // Si la sessió encara és vàlida, actualitzem el temps d'activitat
        $_SESSION['start_time'] = time();
    }

} else {
    // Si l'usuari no està loguejat, el redirigim a la pàgina index.php
    header("Location: index.php");
    exit();
}
?>
