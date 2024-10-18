<?php

session_start();


session_destroy();

// Establecer una cookie temporal para el mensaje de deslogueo
setcookie('logout_exitos', '1', time() + 3600, '/');


header("Location: ../index.php");
exit();
?>
