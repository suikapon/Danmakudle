<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danmakudle - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'header.php'; ?>
    <main class="container flex-grow-1 ">
        <div class="card-log">
            <div class="text-center">
                <h5>Crear cuenta</h5>
            </div>
            <div class="card-log-body">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_GET['error'] === 'username' ? 'El username ya está en uso' : 'Error al registrarse'; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['ok'])): ?>
                    <div class="alert alert-success">Cuenta creada correctamente. <a href="login.php"
                            class="text-info">Inicia sesión</a></div>
                <?php endif; ?>
                <form action="config/registro.php" method="POST" id="formRegistro" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Tu username">
                    </div>
                    <div class="mb-3">
                        <label>Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Contraseña">
                    </div>
                    <div class="mb-3">
                        <label>Confirmar contraseña</label>
                        <input type="password" class="form-control" name="password_confirmar" id="password_confirmar"
                            placeholder="Repite la contraseña">
                    </div>
                    <div class="mb-3">
                        <label>Avatar <small>(opcional)</small></label>
                        <input type="file" class="form-control" name="avatar" accept="image/*">
                    </div>
                    <button class="btn btn-danmaku w-100" type="submit">
                        <i class="bi bi-person-plus"></i> Registrarse
                    </button>
                </form>
                <div class="text-center mt-3">
                    <small>¿Ya tienes cuenta? <a href="login.php" class="text-info">Inicia sesión</a></small>
                </div>
            </div>
        </div>
    </main>
</body>

</html>