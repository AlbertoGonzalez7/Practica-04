<?php
session_start();
require_once "../Database/connexio.php"; // Asegúrate de tener este archivo con los detalles de conexión a la base de datos

// Verifica si es login o registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $usuari = trim($_POST['usuari']);
    $password = trim($_POST['pass']);

    if ($accion == 'login') {
        // Procesar login
        if (!empty($usuari) && !empty($password)) {
            $sql = "SELECT * FROM usuaris WHERE usuari = :usuari";
            $stmt = $db->prepare($sql);
            $stmt->execute(['usuari' => $usuari]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['contrasenya'])) {
                $_SESSION['usuario'] = $user['usuari'];
                $_SESSION['missatge_exit'] = "Login correcto!";
                header("Location: ../index_usuari.php"); // Redirige a index_usuari.php tras el login
                exit();
            } else {
                $_SESSION['missatge'] = "Usuario o contraseña incorrectos";
                header("Location: login.php");
                exit();
            }
            
        }
    } elseif ($accion == 'registro') {
        // Procesar registro
        $confirm_password = trim($_POST['confirm_pass']);

        // Verifica que las contraseñas coincidan
        if ($password === $confirm_password) {
            // Regex para validar la contraseña
            if (preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
                // Verifica que el usuario no exista ya
                $sql = "SELECT * FROM usuaris WHERE usuari = :usuari";
                $stmt = $db->prepare($sql);
                $stmt->execute(['usuari' => $usuari]);
                $existing_user = $stmt->fetch();

                if ($existing_user) {
                    $_SESSION['missatge'] = "El nombre de usuario ya está registrado";
                    header("Location: login.php");
                } else {
                    // Inserta el nuevo usuario en la base de datos
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO usuaris (usuari, contrasenya) VALUES (:usuari, :password)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(['usuari' => $usuari, 'password' => $hashed_password]);

                    $_SESSION['missatge_exit'] = "Registro exitoso!";
                    header("Location: login.php");
                }
            } else {
                $_SESSION['missatge'] = "La contraseña no cumple con los requisitos";
                header("Location: login.php");
            }
        } else {
            $_SESSION['missatge'] = "Las contraseñas no coinciden";
            header("Location: login.php");
        }
    }
}
?>
