<?php
session_start();

if (isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

require_once 'config/config.php';
require_once 'config/consultas.php';

if (isset($_COOKIE['dm_email']) && isset($_COOKIE['dm_password'])) {
    $email = $_COOKIE['dm_email'];
    $password = $_COOKIE['dm_password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $_SESSION['email'] = $email;
        $_SESSION['rol'] = $row['rol'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['id_usuario'] = $row['id_usuario'];
        header("Location: index.php");
        exit;
    }
}

$emailGuardado = $_COOKIE['dm_email'] ?? '';
$recordarGuardado = isset($_COOKIE['dm_recordar']) && $_COOKIE['dm_recordar'] === '1';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danmakudle - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'header.php'; ?>

    <main class="container flex-grow-1 d-flex align-items-center justify-content-center">
        <div style="width: 100%; max-width: 400px;" class="px-3">

            <div class="card-log">
                <div class="text-center mb-3">
                    <h5>Iniciar sesión</h5>
                </div>
                <div class="card-log-body">

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">Correo o contraseña incorrectos</div>
                    <?php endif; ?>

                    <form action="config/login.php" method="POST">
                        <div class="mb-3">
                            <label for="correoLogin">Correo electrónico</label>
                            <input type="email" class="form-control" id="correoLogin" name="email"
                                placeholder="name@example.com" value="<?php echo htmlspecialchars($emailGuardado); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="passwordLogin">Contraseña</label>
                            <input type="password" class="form-control" id="passwordLogin" name="password"
                                placeholder="Contraseña" required>
                        </div>

                        <div class="mb-3 d-flex align-items-center gap-2">
                            <input type="checkbox" class="form-check-input" name="recordar" id="recordar" <?php echo $recordarGuardado ? 'checked' : ''; ?>>
                            <label for="recordar" class="mb-0">Recordar sesión</label>
                        </div>

                        <button class="btn btn-danmaku w-100" type="submit">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
                        </button>

                        <div class="text-center mt-3">
                            <small>¿No tienes cuenta? <a href="registro.php" class="text-info"><br>Regístrate</a><br>
                        o<br><a href="index.php" class="text-info">Juega sin cuenta</a></small>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
</body>

</html>