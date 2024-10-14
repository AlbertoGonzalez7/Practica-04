<?php
session_start();
include 'connexio.php'; // Asegúrate de tener este archivo con los detalles de conexión a la DB

// Verifica si es login o registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($accion == 'login') {
        // Procesar login
        if (!empty($username) && !empty($password)) {
            $sql = "SELECT * FROM usuarios WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['usuario'] = $user['username'];
                $_SESSION['missatge_exit'] = "Login correcto!";
                header("Location: bienvenida.php"); // Redirige a la página deseada
            } else {
                $_SESSION['missatge'] = "Usuario o contraseña incorrectos";
                header("Location: login.php");
            }
        }
    } elseif ($accion == 'registro') {
        // Procesar registro
        $confirm_password = trim($_POST['confirm_password']);

        // Verifica que las contraseñas coincidan
        if ($password === $confirm_password) {
            // Regex para validar la contraseña
            if (preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
                // Verifica que el usuario no exista ya
                $sql = "SELECT * FROM usuarios WHERE username = :username";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['username' => $username]);
                $existing_user = $stmt->fetch();

                if ($existing_user) {
                    $_SESSION['missatge'] = "El nombre de usuario ya está registrado";
                    header("Location: login.php");
                } else {
                    // Inserta el nuevo usuario en la base de datos
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO usuarios (username, password) VALUES (:username, :password)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['username' => $username, 'password' => $hashed_password]);

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
