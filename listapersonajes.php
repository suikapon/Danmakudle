<?php
require_once 'config/config.php';

$stmt = $conn->query('SELECT * FROM personajes');
$personajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Danmakudle</title>
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