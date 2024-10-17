<?php
session_start();
require_once "../Database/connexio.php"; // Asegúrate de tener este archivo con los detalles de conexión a la base de datos

$connexio = new PDO("mysql:host=$db_host; dbname=$db_nom", $db_usuari, $db_password);

$errors = [];
$usuari = $password = $confirm_password = ""; // Inicializamos las variables

// Verificamos si se ha enviado el formulario:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $usuari = trim($_POST['usuari']);
    $password = trim($_POST['pass']);

    if ($accion == 'login') {
        // Procesar login
        if (empty($usuari)) {
            $errors[] = "El camp 'Usuari' és obligatori.";
        }
        if (empty($password)) {
            $errors[] = "El camp 'Contrasenya' és obligatori.";
        }

        // Si hay errores, los guardamos
        if (!empty($errors)) {
            $_SESSION['missatge'] = implode("<br>", $errors);
            $_SESSION['usuari'] = $usuari; // Guardamos el valor del usuario
        } else {
            // Verificar que el usuario existe
            $sql = "SELECT * FROM usuaris WHERE usuari = :usuari";
            $stmt = $connexio->prepare($sql);
            $stmt->execute(['usuari' => $usuari]);
            $user = $stmt->fetch();
        
            if ($user && password_verify($password, $user['contrasenya'])) {
                // Autenticación exitosa
                $_SESSION['usuari'] = $user['usuari'];
                $_SESSION['user_id'] = $user['id']; // Guardar el id del usuario en la sesión
                $_SESSION['start_time'] = time(); // Guardar la hora de inicio de sesión
                header("Location: ../index_usuari.php");
                exit();
            } else {
                // Si las credenciales no son correctas
                $_SESSION['missatge'] = "Usuari o contrasenya incorrectes";
                $_SESSION['usuari'] = $usuari; // Guardamos el valor del usuario
            }
        }
        
    } elseif ($accion == 'registro') {
        // Procesar registro
        $confirm_password = trim($_POST['confirm_pass']);
        $usuari_reg = trim($_POST['usuari_reg']);

        if (empty($usuari_reg)) {
            $errors[] = "El camp 'Usuari' és obligatori.";
        }
        if (empty($password)) {
            $errors[] = "El camp 'Contrasenya' és obligatori.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Les contrasenyes no coincideixen.";
        }

        // Verificamos que la contraseña cumpla con los requisitos
        if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
            $errors[] = "La contrasenya ha de contenir 8 caràcters, una mayúscula, un número i un símbol.";
        }

        // Si hay errores, los guardamos
        if (!empty($errors)) {
            $_SESSION['missatge'] = implode("<br>", $errors); // Mensajes de error separados por un salto de línea
            $_SESSION['usuari_reg'] = $usuari_reg; // Guardamos el valor del usuario
        } else {
            // Verificamos si el usuario ya existe
            $sql = "SELECT * FROM usuaris WHERE usuari = :usuari";
            $stmt = $connexio->prepare($sql);
            $stmt->execute(['usuari' => $usuari_reg]);
            $existing_user = $stmt->fetch();

            if ($existing_user) {
                $_SESSION['missatge'] = "El nom d'usuari ja està agafat";
                $_SESSION['usuari_reg'] = $usuari_reg; // Guardamos el valor del usuario
            } else {
                // Insertamos el nuevo usuario en la base de datos
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO usuaris (usuari, contrasenya) VALUES (:usuari, :password)";
                $stmt = $connexio->prepare($sql);
                $stmt->execute(['usuari' => $usuari_reg, 'password' => $hashed_password]);

                $_SESSION['missatge_exit'] = "Registrat amb èxit!";
                $_SESSION['usuari_reg'] = ""; // Limpiar el valor del usuario
            }
        }
    }

    // Redirigimos a login.php para mostrar los mensajes de error o éxito
    header("Location: login.php");
    exit();
}

// Limpiar las variables de sesión al cargar la página por primera vez
if (!isset($_SESSION['usuari'])) {
    $_SESSION['usuari'] = "";
}
if (!isset($_SESSION['usuari_reg'])) {
    $_SESSION['usuari_reg'] = "";
}
?>
