<?php
$servername = "dpg-d7jsv5pj2pic73clrgbg-a.frankfurt-postgres.render.com"; // host
$username = "danmakudle_5w7q_user";
$password = "jDkoMkOnHXF0ju66VTTaBPF85f9frDyH";
$dbname = "danmakudle_5w7q";

try {
    $conn = new PDO("pgsql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Falló: " . $e->getMessage());
}

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
    <!-- por ahora está en php puro, sin laravel -->
    <h2>Personajes prueba conexión con la BD</h2>
    <ul>
       <?php foreach($personajes as $p): ?>
            <li>
                <br>
                id: <?=$p['id_personaje']?><br>
                nombre: <?= $p['nombre'] ?><br>
                videojuego: <?= $p['videojuego'] ?><br>
                año lanzamiento: <?= $p['ano_lanzamiento'] ?><br>
                ubicación: <?= $p['ubicacion'] ?><br>
                ocupación: <?= $p['ocupacion'] ?><br>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>