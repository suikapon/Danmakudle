<?php
ob_start();

require_once 'config/config.php';
require_once 'config/consultas.php';

$filtros = ['puntos', 'partidas_ganadas','racha_actual', 'racha_max', 'partidas_jugadas'];
// por defecto se selecciona puntos
$orden = isset($_GET['orden']) && in_array($_GET['orden'], $filtros) ? $_GET['orden']:'puntos';

$stmt = $conn->prepare("
    SELECT u.username, u.avatar, e.puntos, e.racha_actual, e.partidas_jugadas, e.partidas_ganadas, e.racha_max
    FROM estadisticas_usuario e
    JOIN usuarios u ON e.id_usuario = u.id_usuario
    ORDER BY e.$orden DESC
    LIMIT 50");
$stmt->execute();
$ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <main class="container flex-grow-1 main-panel">

    <h1>RANKING</h1>
    <div>
        <a href="?orden=puntos" class="btn btn-sm <?= $orden == 'puntos' ? 'btn-danmaku' : 'btn-secondary' ?>">Puntos</a>
        <a href="?orden=partidas_ganadas" class="btn btn-sm <?= $orden == 'partidas_ganadas' ? 'btn-danmaku' : 'btn-secondary' ?>">Victorias</a>
        <a href="?orden=racha_actual" class="btn btn-sm <?= $orden == 'racha_actual' ? 'btn-danmaku' : 'btn-secondary' ?>">Racha Actual</a>
        <a href="?orden=racha_max" class="btn btn-sm <?= $orden == 'racha_max' ? 'btn-danmaku' : 'btn-secondary' ?>">Racha Máx.</a>
        <a href="?orden=partidas_jugadas" class="btn btn-sm <?= $orden == 'partidas_jugadas' ? 'btn-danmaku' : 'btn-secondary' ?>">Partidas</a>
    </div>
    <div class="tabla-scroll">
    <table class="tabla-ranking">
    <thead>
        <tr>
            <th>#</th>
            <th>Avatar</th>
            <th>Usuario</th>
            <th>Puntos</th>
            <th>Victorias</th>
            <th>Racha Actual</th>
            <th>Racha máx</th>
            <th>Partidas</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ranking as $pos => $r): ?>
        <?php
            $posicionColor = 'otro';
            if ($pos == 0) $posicionColor = 'oro';
            if ($pos == 1) $posicionColor = 'plata';
            if ($pos == 2) $posicionColor = 'bronze';
        ?>
        <tr class=<?=$posicionColor?>>
            <td>#<?= $pos+1 ?></td>
            <td><img src="<?= $r['avatar'] ?>" width="50" height="50" class="rounded-circle"></td>
            <td><?= $r['username'] ?></td>
            <td><?= $r['puntos'] ?></td>
            <td><?= $r['partidas_ganadas'] ?></td>
            <td><?= $r['racha_actual'] ?></td>
            <td><?= $r['racha_max'] ?></td>
            <td><?= $r['partidas_jugadas'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
    </div>
</main>
</body>
</html>