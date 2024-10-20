<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

session_start();
require_once "../Database/connexio.php";

$connexio = new PDO("mysql:host=$db_host; dbname=$db_nom", $db_usuari, $db_password);

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
            $errors[] = "El camp 'Usuari' és obligatori."; // Error si el camp usuari està buit
        }
        if (empty($password)) {
            $errors[] = "El camp 'Contrasenya' és obligatori."; // Error si el camp contrasenya està buit
        }

        // Si hi ha errors, els guardem
        if (!empty($errors)) {
            $_SESSION['missatge'] = implode("<br>", $errors); // Guardem els missatges d'error
            $_SESSION['usuari'] = $usuari; // Guardem el valor de l'usuari
        } else {
            // Verificar que l'usuari existeix
            $sql = "SELECT * FROM usuaris WHERE usuari = :usuari";
            $stmt = $connexio->prepare($sql);
            $stmt->execute(['usuari' => $usuari]);
            $user = $stmt->fetch(); // Obtenim l'usuari de la base de dades
            
            if ($user && password_verify($password, $user['contrasenya'])) {
                // Autenticació exitosa
                $_SESSION['usuari'] = $user['usuari'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['start_time'] = time(); // Guardem la hora d'inici de sessió

                setcookie('login_exitos', '1', time() + 60, '/'); // Creem una cookie de sessió

                header("Location: ../index_usuari.php"); // Redirigim a la pàgina d'usuari
                exit();
            } else {
                // Si les credencials no són correctes
                $_SESSION['missatge'] = "Usuari o contrasenya incorrectes"; // Missatge d'error
                $_SESSION['usuari'] = $usuari; // Guardem el valor de l'usuari
            }
        }
        
    } elseif ($accion == 'registro') {
        // Processar registre
        $confirm_password = trim($_POST['confirm_pass']);
        $usuari_reg = trim($_POST['usuari_reg']);

        if (empty($usuari_reg)) {
            $errors[] = "El camp 'Usuari' és obligatori."; // Error si el camp usuari per registrar està buit
        }
        if (empty($password)) {
            $errors[] = "El camp 'Contrasenya' és obligatori."; // Error si el camp contrasenya està buit
        }
        if ($password !== $confirm_password) {
            $errors[] = "Les contrasenyes no coincideixen."; // Error si les contrasenyes no coincideixen
        }

        // Verifiquem que la contrasenya compleixi els requisits
        if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
            $errors[] = "La contrasenya ha de contenir 8 caràcters, una mayúscula, un número i un símbol."; // Error si la contrasenya no compleix els requisits
        }

        // Si hi ha errors, els guardem
        if (!empty($errors)) {
            $_SESSION['missatge'] = implode("<br>", $errors); // Guardem els missatges d'error separats per un salt de línia
            $_SESSION['usuari_reg'] = $usuari_reg;
        } else {
            // Verifiquem si l'usuari ja existeix
            $sql = "SELECT * FROM usuaris WHERE usuari = :usuari";
            $stmt = $connexio->prepare($sql);
            $stmt->execute(['usuari' => $usuari_reg]);
            $existing_user = $stmt->fetch(); // Busquem si ja existeix l'usuari

            if ($existing_user) {
                $_SESSION['missatge'] = "El nom d'usuari ja està agafat"; // Missatge d'error si l'usuari ja existeix
                $_SESSION['usuari_reg'] = $usuari_reg;
            } else {
                // Insertem el nou usuari a la base de dades
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash de la contrasenya
                $sql = "INSERT INTO usuaris (usuari, contrasenya) VALUES (:usuari, :password)";
                $stmt = $connexio->prepare($sql);
                $stmt->execute(['usuari' => $usuari_reg, 'password' => $hashed_password]); // Executem la inserció

                $_SESSION['missatge_exit'] = "Registrat amb èxit!"; // Missatge d'èxit
                $_SESSION['usuari_reg'] = "";
            }
        }
    }

    // Redirigim a login.php per mostrar els missatges d'error o èxit
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
