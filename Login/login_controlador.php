<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

session_start();
require_once "../Model/UsuariModel.php"; // Incloem el model d'usuaris

$errors = [];
$usuari = $password = $confirm_password = "";

// Verifiquem si s'ha enviat el formulari:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion']; // Obtenim l'acció (login o registre)
    $usuari = trim($_POST['usuari']);
    $password = trim($_POST['pass']);

    if ($accion == 'login') {
        // Processar login
        if (empty($usuari)) {
            $errors[] = "El camp 'Usuari' és obligatori.";
        }
        if (empty($password)) {
            $errors[] = "El camp 'Contrasenya' és obligatori.";
        }

        if (!empty($errors)) {
            $_SESSION['missatge'] = implode("<br>", $errors);
            $_SESSION['usuari'] = $usuari;
        } else {
            // Verificar que l'usuari existeix
            $user = obtenirUsuariPerNom($usuari); // Fem servir la funció del model

            if ($user && password_verify($password, $user['contrasenya'])) {
                // Autenticació exitosa
                $_SESSION['usuari'] = $user['usuari'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['start_time'] = time(); 

                setcookie('login_exitos', '1', time() + 60, '/');

                header("Location: ../Vistes/index_usuari.php");
                exit();
            } else {
                // Credencials incorrectes
                $_SESSION['missatge'] = "Usuari o contrasenya incorrectes";
                $_SESSION['usuari'] = $usuari;
            }
        }

    } elseif ($accion == 'registro') {
        // Processar registre
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

        if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
            $errors[] = "La contrasenya ha de contenir 8 caràcters, una mayúscula, un número i un símbol.";
        }

        if (!empty($errors)) {
            $_SESSION['missatge'] = implode("<br>", $errors);
            $_SESSION['usuari_reg'] = $usuari_reg;
        } else {
            // Verifiquem si l'usuari ja existeix
            $existing_user = obtenirUsuariPerNom($usuari_reg);

            if ($existing_user) {
                $_SESSION['missatge'] = "El nom d'usuari ja està agafat";
                $_SESSION['usuari_reg'] = $usuari_reg;
            } else {
                // Insertem el nou usuari
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                inserirUsuari($usuari_reg, $hashed_password); // Funció del model

                $_SESSION['missatge_exit'] = "Registrat amb èxit!";
                $_SESSION['usuari_reg'] = "";
            }
        }
    }

    header("Location: login.php");
    exit();
}

// Netejar les variables de sessió al carregar la pàgina per primera vegada
if (!isset($_SESSION['usuari'])) {
    $_SESSION['usuari'] = "";
}
if (!isset($_SESSION['usuari_reg'])) {
    $_SESSION['usuari_reg'] = "";
}
?>
