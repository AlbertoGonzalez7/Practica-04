<!DOCTYPE html>

<!-- # Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions -->

<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<!-- Mostrar missatge d'alerta si esta la cookie activa -->
    <?php if (isset($_COOKIE['logout_exitos'])): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            T'has desloguejat amb èxit
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <!-- Eliminem la cookie un cop mostrat el missatghe -->
        <?php setcookie('logout_exitos', '', time() - 3600, '/'); ?>
    <?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>