<?php
require_once 'config/config.php';
require_once 'config/consultas.php';

$juegos = getJuegos($conn);
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de juegos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'header.php'; ?>

    <h1>DANMAKUDLE</h1>

    <h2>Juegos prueba conexión con la BD</h2>
    <ul>
        <?php foreach($juegos as $j): ?>
            <li>
                <br>
                <img src="img/juegos/<?= $j['imagen'] ?>" width=150 height=150><br>
                id: <?= $j['id'] ?><br>
                nombre: <?= $j['nombre'] ?><br>
                año: <?= $j['año'] ?><br>
                tipo: <?= $j['tipo'] ?><br>
                plataforma: <?= $j['plataforma'] ?><br>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>