<?php
# Alberto González Benítez, 2n DAW, Pràctica 02 - Connexions PDO
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/estil_formulari.css">
    <title>Document</title>
    <script>
        function toggleForm(type) {
            if (type === 'login') {
                document.getElementById('login-form').style.display = 'block';
                document.getElementById('register-form').style.display = 'none';
            } else {
                document.getElementById('login-form').style.display = 'none';
                document.getElementById('register-form').style.display = 'block';
            }
        }

        function validatePassword() {
            const password = document.getElementById('register-pass').value;
            const confirmPass = document.getElementById('confirm-pass').value;
            const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!regex.test(password)) {
                alert("La contrasenya ha de tenir almenys 8 caràcters, una majúscula, un número i un símbol.");
                return false;
            }

            if (password !== confirmPass) {
                alert("Les contrasenyes no coincideixen.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>   
    <div class="form">
        <div class="title">Login / Registre</div>
        <div class="subtitle">Selecciona una opció</div><br>


        <div>
            <button class="button-90" type="button" onclick="toggleForm('login')">Login</button>
            <button class="button-90" type="button" onclick="toggleForm('register')">Registre</button>
        </div>

        <!-- Formulario de login -->
        <form id="login-form" method="POST" action="login_controlador.php" style="display:block;">
        <input type="hidden" name="accion" value="login">
            <div class="input-container ic2">
                <input name="usuari" class="input" type="text" placeholder=" " required />
                <div class="cut"></div>
                <label for="usuari" class="placeholder">Nom d'usuari</label>
            </div>
            <div class="input-container ic2">
                <input name="pass" class="input" type="password" placeholder=" " required />
                <div class="cut cut-short"></div>
                <label for="pass" class="placeholder">Contrasenya</label>
            </div>
            <br>
            <input type="submit" value="Login" class="insertar" name="entrar">
        </form>

        <!-- Formulario de registro -->
        <form id="register-form" method="POST" action="login_controlador.php" style="display:none;" onsubmit="return validatePassword()">
        <input type="hidden" name="accion" value="registro">    
        <div class="input-container ic2">
                <input name="usuari" class="input" type="text" placeholder=" " required />
                <div class="cut"></div>
                <label for="usuari" class="placeholder">Nom d'usuari</label>
            </div>
            <div class="input-container ic2">
                <input id="register-pass" name="pass" class="input" type="password" placeholder=" " required />
                <div class="cut cut-short"></div>
                <label for="pass" class="placeholder">Contrasenya</label>
            </div>
            <div class="input-container ic2">
                <input id="confirm-pass" name="confirm_pass" class="input" type="password" placeholder=" " required />
                <div class="cut cut-short"></div>
                <label for="confirm-pass" class="placeholder">Confirma la contrasenya</label>
            </div>

            <br>
            <input type="submit" value="Registre" class="insertar" name="registrar">
        </form>


        <a href='../index.php'><br>
          <button class='tornar' role='button'>Tornar</button>
        </a>;
    
        <!-- Mensajes de sesión -->
        <?php
        if (isset($_SESSION['missatge_exit'])) {
            echo "<p style='color: green;'>" . ($_SESSION['missatge_exit']) . "</p>";
            unset($_SESSION['missatge_exit']);
        } else if (isset($_SESSION['missatge'])) {
            echo "<p style='color: red;'>" . ($_SESSION['missatge']) . "</p>";
            unset($_SESSION['missatge']);
        }
        ?>
    </div>
</body>
</html>
