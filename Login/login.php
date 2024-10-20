<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

session_start();

// Establecer la vista predeterminada al cargar la página (por defecto 'login')
if (!isset($_POST['form_type'])) {
    $form_type = 'login';
} else {
    $form_type = $_POST['form_type'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/estil_formulari.css">
    <title>Document</title>
</head>
<body>   
    <div class="form">
        <div class="title">Login / Registre</div>
        <div class="subtitle">Selecciona una opció</div><br>

        <div>
            <form method="POST">
                <input type="hidden" name="form_type" value="login">
                <button class="button-90" type="submit">Login</button>
                <br>
                <br>
            </form>
            <form method="POST">
                <input type="hidden" name="form_type" value="register">
                <button class="button-90" type="submit">Registre</button>
            </form>
        </div>

        <!-- Formulari de login -->
        <?php if ($form_type === 'login'): ?>
        <form id="login-form" method="POST" action="login_controlador.php">
            <input type="hidden" name="accion" value="login">
            <div class="input-container ic2">
                <input name="usuari" class="input" type="text" placeholder=" " value="<?php echo isset($_SESSION['usuari']) ? htmlspecialchars($_SESSION['usuari']) : ''; ?>"/>
                <div class="cut"></div>
                <label for="usuari" class="placeholder">Nom d'usuari</label>
            </div>
            <div class="input-container ic2">
                <input name="pass" class="input" type="password" placeholder=" " />
                <div class="cut cut-short"></div>
                <label for="pass" class="placeholder">Contrasenya</label>
            </div>
            <br>
            <input type="submit" value="Login" class="insertar" name="entrar">
        </form>
        <?php endif; ?>

        <!-- Formulari de registre -->
        <?php if ($form_type === 'register'): ?>
        <form id="register-form" method="POST" action="login_controlador.php" onsubmit="return validatePassword()">
            <input type="hidden" name="accion" value="registro">    
            <div class="input-container ic2">
                <input name="usuari_reg" class="input" type="text" placeholder=" " value="<?php echo isset($_SESSION['usuari_reg']) ? htmlspecialchars($_SESSION['usuari_reg']) : ''; ?>"/>
                <div class="cut"></div>
                <label for="usuari_reg" class="placeholder">Nom d'usuari</label>
            </div>
            <div class="input-container ic2">
                <input id="register-pass" name="pass" class="input" type="password" placeholder=" "/>
                <div class="cut cut-short"></div>
                <label for="pass" class="placeholder">Contrasenya</label>
            </div>
            <div class="input-container ic2">
                <input id="confirm-pass" name="confirm_pass" class="input" type="password" placeholder=" "/>
                <div class="cut cut-short"></div>
                <label for="confirm-pass" class="placeholder">Confirma la contrasenya</label>
            </div>
            <br>
            <input type="submit" value="Registre" class="insertar" name="registrar">
        </form>
        <?php endif; ?>

        <a href='../index.php'><br>
          <button class='tornar_login' role='button'>Tornar</button>
        </a>

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
