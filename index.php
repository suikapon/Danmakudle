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

$stmt = $conn->query('SELECT * FROM personajes'); // limite prueba
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

    <!-- index básico de placeholder -->
    <div class="modos">
        <div class="modo">
            <h2>Listado de personajes</h2>
            <p><a href="#listapersonajes.php">Lista</a></p>
        </div>
        <div class="modo">
            <h2>Modo 2</h2>
            <p>Descripción</p>
        </div>
        <div class="modo">
            <h2>Modo 3</h2>
            <p>Descripción</p>
        </div>
    </div>
</body>
</html>