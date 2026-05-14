<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danmakudle</title>
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
                <p class="text-secondary">Demuestra cuánto sabes sobre el género Bullet Hell</p>
            </div>
        </div>

        <!-- las cajitas/tarjetas con los modos disponibles -->
        <div class="row g-4">
            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/reimu.png" class="card-img-top mx-auto mt-6">
                        <h2>Adivina el personaje</h2>
                        <p class="text-secondary">Adivina el nombre del personaje</p>
                        <a href="modo1.php" class="boton stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/eosd.png" class="card-img-top mx-auto mt-6">
                        <h2>Adivina el videojuego</h2>
                        <p class="text-secondary">Adivina el nombre del videojuego</p>
                        <a href="modo2.php" class="boton stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/silueta.png" class="card-img-top mx-auto mt-6">
                        <h2>Adivina la silueta</h2>
                        <p class="text-secondary">Adivina el personaje de la silueta</p>
                        <a href="modo3.php" class="boton stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/yinyangorb.png" class="card-img-top mx-auto mt-6">
                        <h2>Lista de personajes</h2>
                        <p class="text-secondary">¡Información acerca de todos los personajes!</p>
                        <a href="listapersonajes.php" class="boton stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/yinyangorb.png" class="card-img-top mx-auto mt-6">
                        <h2>Lista de juegos</h2>
                        <p class="text-secondary">¡Información acerca de todos los juegos!</p>
                        <a href="listajuegos.php" class="boton stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/yinyangorb.png" class="card-img-top mx-auto mt-6">
                        <h2>Lista de personajes</h2>
                        <p class="text-secondary">¡Información acerca de todos los personajes!</p>
                        <a href="listapersonajes.php" class="boton stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>

    </main>
</body>

</html>