<?php
require_once 'config/config.php';
require_once 'config/consultas.php';

$personajes = getPersonajes($conn)

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

    <h1>DANMAKUDLE</h1>

    <h2>Personajes prueba conexión con la BD</h2>
    <ul>
       <?php foreach($personajes as $p): ?>
            <li>
                <br>
                <img src="img/pj/<?=$p['imagen']?>" width=50 height=50>
                id: <?=$p['id_personaje']?><br>
                nombre: <?= $p['nombre'] ?><br>
                debut: <?= $p['debut'] ?><br>
                stage: <?= $p['stage'] ?><br>
                ubicación: <?= $p['ubicacion'] ?><br>
                especie: <?= $p['especie'] ?><br>
                jugable: <?= $p['jugable'] ?><br>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>