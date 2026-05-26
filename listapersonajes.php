<?php
ob_start();

require_once 'config/config.php';
require_once 'config/consultas.php';

$personajes = getPersonajes($conn);

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de personajes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'header.php';?>

    <main class="container flex-grow-1">
        <div class="row mt-4 text-center">
            <div class="col">
                <h1>Lista de personajes</h1>
                <p class="subtitulo">Todos los personajes de Danmakudle</p>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach($personajes as $p): ?>
            <div class="col-md-3 col-sm-6 mt-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column text-center">
                        <img src="img/pj/<?= $p['imagen'] ?>" class="card-img-top mx-auto" width=100 height=100 style="object-fit: contain;">
                        <h2 class="mt-2"><?= $p['nombre'] ?></h2>
                        <p class="subtitulo mb-1 text-start">Debut: <?= $p['debut'] ?></p>
                        <p class="subtitulo mb-1 text-start">Stage: <?= $p['stage'] ?></p>
                        <p class="subtitulo mb-1 text-start">Especie: <?= $p['especie'] ?></p>
                        <p class="subtitulo mb-1 text-start">Ubicación: <?= $p['ubicacion'] ?></p>
                        <p class="subtitulo mb-0 text-start">Jugable: <?= $p['jugable'] ? 'Sí' : 'No' ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>