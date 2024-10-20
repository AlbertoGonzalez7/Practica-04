<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

session_start();


session_destroy();

// Establim la cookie
setcookie('logout_exitos', '1', time() + 3600, '/');

header("Location: ../index.php");

exit();
?>
