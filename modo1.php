<?php
session_start();
//unset($_SESSION['intentos']); // para limpiar los intentos
require_once 'config/config.php';
require_once 'config/consultas.php';

// cargamos todos los personajes de la base de datos
$personajes = getPersonajes($conn);

// guardamos en la sesión el personaje a adivinar para que no se resetee
if (!isset($_SESSION['pjAdivinar']))
{
    $pjAdivinar = getPersonajeAleatorio(($conn));
    $_SESSION['pjAdivinar'] = $pjAdivinar;
    $_SESSION['intentos'] = [];
}
$pjAdivinar = $_SESSION['pjAdivinar'];

// preparar nombres y la imagen de cada personaje para pasárselo al javascript
$datos = [];
foreach ($personajes as $p)
{
    $datos[] = ['nombre' => $p['nombre'], 'imagen' => $p['imagen']];
}

// procesamor el intento enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['personaje_elegido']))
{
    foreach($personajes as $p)
    {
        if ($p['nombre'] == $_POST['personaje_elegido'])
        {
            $_SESSION['intentos'][] =$p;
            break;
        }
    }
}
// recuperar los intentos de la sesión para recorrerlos
$intentos = $_SESSION['intentos'];
?>

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
    <?php include 'header.php';?>

    <h1>Adivina el personaje</h1>

    <p>personaje a adivinar: <?= $pjAdivinar['nombre'] ?></p>

    <form method="POST">
        <div style="position:relative; display:inline-block">
            <input type="text" id="searchInput" placeholder="Escribe un nombre..." autocomplete="off">
            <div id="dropdown" style="border:1px solid #ccc; max-height:200px; overflow-y:auto; display:none; position:absolute; width:100%; z-index:999; background:white;"></div>
            <!-- se manda el hidden para que no se envíen datos erroneos -->
            <input type="hidden" name="personaje_elegido" id="personajeElegido">
        </div>
        <button type="submit">Adivinar</button>
    </form>

    <?php foreach($intentos as $i):?>
    <div>
        <span>Nombre: <?=$i['nombre']; ?> - Especie: <?=$i['especie_normalizada'];?></span>
    </div>
    <?php endforeach ?>

    <!-- pasarle los datos al archivo javascript -->
    <script>
        const datos = <?= json_encode($datos,JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <script src="js/buscador.js"></script>
</body>
</html>