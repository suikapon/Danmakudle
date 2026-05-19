<?php ob_start(); ?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danmakudle</title>
    <?php include 'meta.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'header.php'; ?>
    <main class="container flex-grow-1">
        <!-- título parte arriba -->
        <div class="row mt-4 text-center">
            <div class="col">
                <h1>Danmakudle</h1>
                <p class="subtitulo">Demuestra cuánto sabes sobre el género Bullet Hell</p>
            </div>
        </div>

        <!-- las cajitas/tarjetas con los modos disponibles -->

        <div class="row mt-4 text-center">
            <div class="col">
                <h2>Modos Diarios</h2>
                <p class="subtitulo">¡Un nuevo reto cada día!</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/reimu.png" class="card-img-top mx-auto mt-6">
                        <h2>Modo Clásico</h2>
                        <p class="subtitulo">Adivina el personaje en base a pistas</p>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalClasicoDiario"
                            class="boton stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/eosd.png" class="card-img-top mx-auto mt-6">
                        <h2>Modo Videojuegos</h2>
                        <p class="subtitulo">Adivina el videojuego</p>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalVideoJuegosDiario"
                            class="boton stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/silueta.png" class="card-img-top mx-auto mt-6">
                        <h2>Modo Siluetas</h2>
                        <p class="subtitulo">Adivina el personaje de la silueta</p>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalSiluetasDiario"
                            class="boton stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/temas.png" class="card-img-top mx-auto mt-6">
                        <h2>Modo Temas</h2>
                        <p class="subtitulo">Adivina el personaje por su tema</p>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalTemasDiario"
                            class="boton stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="row mt-4 text-center">
                <div class="col">
                    <h2>Modos Infinitos</h2>
                    <p class="subtitulo">¡Juega las veces que quieras!</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4 mt-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column text-center">
                            <img src="img/reimu.png" class="card-img-top mx-auto mt-6">
                            <h2>Modo Clásico</h2>
                            <p class="subtitulo">Adivina el personaje en base a pistas</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalClasicoInfinito"
                                class="boton stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column text-center">
                            <img src="img/eosd.png" class="card-img-top mx-auto mt-6">
                            <h2>Modo Videojuegos</h2>
                            <p class="subtitulo">Adivina el videojuego</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalVideoJuegosInfinito"
                                class="boton stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column text-center">
                            <img src="img/silueta.png" class="card-img-top mx-auto mt-6">
                            <h2>Modo Siluetas</h2>
                            <p class="subtitulo">Adivina el personaje de la silueta</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalSiluetasInfinito"
                                class="boton stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column text-center">
                            <img src="img/temas.png" class="card-img-top mx-auto mt-6">
                            <h2>Modo Temas</h2>
                            <p class="subtitulo">Adivina el personaje por su tema</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalTemasInfinito"
                                class="boton stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="row mt-4 text-center">
                    <div class="col">
                        <h2 class="text-center mb-4">Información</h2>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column text-center">
                            <img src="img/yinyangorb.png" class="card-img-top mx-auto mt-6">
                            <h2>Lista de juegos</h2>
                            <p class="subtitulo">¡Información acerca de todos los juegos!</p>
                            <a href="listajuegos.php" class="boton stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column text-center">
                            <img src="img/yinyangorb.png" class="card-img-top mx-auto mt-6">
                            <h2>Lista de personajes</h2>
                            <p class="subtitulo">¡Información acerca de todos los personajes!</p>
                            <a href="listapersonajes.php" class="boton stretched-link"></a>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/017.png" class="card-img-top mx-auto mt-6">
                        <h2>Modo Extra</h2>
                        <p class="subtitulo">???</p>
                        <a href="modoExtra.php" class="boton stretched-link"></a>
                    </div>
                </div>
            </div> -->
            </div>
    </main>

    <?php
    $urlModo = 'modos/diario/modoClasico.php';
    $modalId = 'modalClasicoDiario';
    include 'modales/modalDificultad.php';

    $urlModo = 'modos/diario/modoPortadas.php';
    $modalId = 'modalVideoJuegosDiario';
    include 'modales/modalDificultad.php';

    $urlModo = 'modos/diario/modoSiluetas.php';
    $modalId = 'modalSiluetasDiario';
    include 'modales/modalDificultad.php';

    $urlModo = 'modos/diario/modoTemas.php';
    $modalId = 'modalTemasDiario';
    include 'modales/modalDificultad.php';


    $urlModo = 'modos/infinito/modoClasico.php';
    $modalId = 'modalClasicoInfinito';
    include 'modales/modalDificultad.php';

    $urlModo = 'modos/infinito/modoPortadas.php';
    $modalId = 'modalVideoJuegosInfinito';
    include 'modales/modalDificultad.php';

    $urlModo = 'modos/infinito/modoSiluetas.php';
    $modalId = 'modalSiluetasInfinito';
    include 'modales/modalDificultad.php';

    $urlModo = 'modos/infinito/modoTemas.php';
    $modalId = 'modalTemasInfinito';
    include 'modales/modalDificultad.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>