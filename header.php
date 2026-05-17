<?php
// comprobación de sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logeado = isset($_SESSION['id_usuario']);
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #000;">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="/index.php">
            <img src="img/danmakudle.png" alt="Danmakudle" height="20">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- los distintos elementos del header -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Inicio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/rankings.php">Rankings</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if ($logeado): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                            data-bs-toggle="dropdown">
                            <img src="<?= $_SESSION['avatar'] ?>" alt="Avatar"
                                class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/perfil.php"><i class="bi bi-person"></i> Mi Perfil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="config/logout.php"><i
                                        class="bi bi-box-arrow-left"></i> Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Iniciar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-danmaku text-white" href="registro.php">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>