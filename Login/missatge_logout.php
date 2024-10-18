
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<!-- Mostrar mensaje de alerta solo si la cookie está presente -->
    <?php if (isset($_COOKIE['logout_exitos'])): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            T'has desloguejat amb èxit
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <!-- Eliminar la cookie para que no vuelva a mostrar el mensaje -->
        <?php setcookie('logout_exitos', '', time() - 3600, '/'); ?>
    <?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>