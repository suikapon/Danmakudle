<?php
require_once 'config/config.php';

$stmt = $conn->query('SELECT * FROM personajes');
$personajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

<body>
    <h1>DANMAKUDLE</h1>
    <h2>Personajes prueba conexión con la BD</h2>
    <ul>
       <?php foreach($personajes as $p): ?>
            <li>
                <br>
                id: <?=$p['id_personaje']?><br>
                nombre: <?= $p['nombre'] ?><br>
                videojuego: <?= $p['videojuego'] ?><br>
                ubicación: <?= $p['ubicacion'] ?><br>
                ocupación: <?= $p['ocupacion'] ?><br>
                especie: <?= $p['especie'] ?><br>
                especie normalizada: <?= $p['especie_normalizada'] ?><br>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>