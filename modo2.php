<?php
session_start();
//session_destroy();

// variable para tener control de cuantas vidas se quieren
// elegir al principio de una partida en vez de poner el número
$vidas = 6;

if (isset($_GET['reset'])) {
    unset($_SESSION['intentosPortada']);
    unset($_SESSION['juegoAdivinar']);
    $_SESSION['vidasJuegos'] = $vidas;
    header('Location: modo2.php');
    exit();
}

require_once 'config/config.php';
require_once 'config/consultas.php';
require_once 'config/funciones.php';

// cargamos todos los juegos de la base de datos
$juegos = getJuegos($conn);

// guardamos en la sesión el juego a adivinar para que no se resetee
if (!isset($_SESSION['juegoAdivinar'])) {
    $juegoAdivinar = getJuegoAleatorio($conn);
    $_SESSION['juegoAdivinar'] = $juegoAdivinar;
    $_SESSION['intentosPortada'] = [];
    $_SESSION['vidasJuegos'] = $vidas;
}

if (!isset($_SESSION['intentosPortada'])) {
    $_SESSION['intentosPortada'] = [];
}
if (!isset($_SESSION['vidasJuegos'])) {
    $_SESSION['vidasJuegos'] = $vidas;
}

$juegoAdivinar = $_SESSION['juegoAdivinar'];

// preparar nombres y la imagen de cada juego para pasárselo al javascript
$datos = [];
foreach ($juegos as $j) {
    $datos[] = ['nombre' => $j['nombre'], 'imagen' => $j['imagen'], 'id' => $j['id']];
}

// procesar el intento enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['juego_elegido'])) {
    // comprobar si ya ha sido intentado el juego
    $juegoYaIntentado = false;
    foreach ($_SESSION['intentosPortada'] as $i) {
        if ($i['nombre'] == $_POST['juego_elegido']) {
            $juegoYaIntentado = true;
            break;
        }
    }
    // agregarlo a los intentos si no está ya intentado
    if (!$juegoYaIntentado) {
        foreach ($juegos as $j) {
            if ($j['nombre'] == $_POST['juego_elegido']) {
                $_SESSION['intentosPortada'][] = $j;

                // restar una vida en fallo
                if ($j['id'] != $juegoAdivinar['id'])
                    $_SESSION['vidasJuegos']--;
                break;
            }
        }
    }
}
// recuperar los intentos de la sesión para recorrerlos
$intentosPortada = $_SESSION['intentosPortada'];

$gano = !empty($intentosPortada) && end($intentosPortada)['id'] == $juegoAdivinar['id'];
$perdio = $_SESSION['vidasJuegos'] <= 0 && !$gano;

//nivel de blur según vidas restantes a menos vidas más se ve la imagen
$blur = $_SESSION['vidasJuegos'] * 5;
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
    <?php include 'header.php'; ?>

    <main class="container d-flex flex-column align-items-center flex-grow-1">
        <!--botones para reiniciar intentos y el juego-->
        <a class="text-center mb-4" href="?reset=todo">cambiar juego</a>

        <h1 class="text-center mb-4">Adivina el juego por la portada</h1>
        <div id="texto-vidas" class="d-flex justify-content-center mb-4">
            <span>Vidas:</span>
            <?php for ($i = 0; $i < $_SESSION['vidasJuegos']; $i++): ?>
                <img src="img/stars/vida.png" width="20" height="20">
            <?php endfor; ?>
        </div>

        <!--portada con blur desaparece al ganar o perder -->
        <img src="img/juegos/<?= $juegoAdivinar['imagen'] ?>" class="portada"
            style="filter: blur(<?= ($gano || $perdio) ? 0 : $blur ?>px)" width=300 height=300>

        <?php if (!$gano && !$perdio): ?>
            <form method="POST">
                <div style="position:relative; display:inline-block">
                    <input type="text" id="searchInput" placeholder="Escribe un título/versión" autocomplete="off"
                        style="width:300px">
                    <div id="dropdown"
                        style="border:1px solid #ccc; max-height:200px; overflow-y:auto; display:none; position:absolute; width:100%; z-index:999; background:white;">
                    </div>
                    <!-- se manda el hidden para que no se envíen datos erroneos -->
                    <input type="hidden" name="juego_elegido" id="juegoElegido">
                </div>
                <button type="submit">Adivinar</button>
            </form>
        <?php endif; ?>

        <table class="tabla-intentos">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Año</th>
                    <th>Tipo</th>
                    <th>Plataforma</th>
                </tr>
            </thead>
            <tbody>
                <!--array invertido porque es más cómodo que el último intento salga arriba del todo-->
                <?php foreach (array_reverse($intentosPortada) as $i):
                    // almacenar el color de cada campo como un estado para que se vea en las comparaciones en el juego usando las clases !!
                    $estadoNombre = estadoSimple($i, $juegoAdivinar, 'id');

                    // año
                    $resultadoAño = compararValor((int) $i['año'], (int) $juegoAdivinar['año']);
                    $estadoAño = ($resultadoAño == 'verde') ? 'verde' : 'rojo';

                    // tipo
                    $estadoTipo = estadoSimple($i, $juegoAdivinar, 'tipo');

                    // plataforma
                    $estadoPlataforma = estadoSimple($i, $juegoAdivinar, 'plataforma');
                    ?>
                    <tr>
                        <td class="<?= $estadoNombre ?>">
                            <img src="img/juegos/<?= $i['imagen'] ?>" width=100 height=100>
                        </td>
                        <td class="<?= $estadoNombre ?>">
                            Touhou <?= $i['id'] ?>
                            </br>
                            <?= $i['nombre'] ?>
                        </td>
                        <td class="<?= $estadoAño ?>">
                            <?= $i['año'] ?>
                            <?= $resultadoAño !== 'verde' ? $resultadoAño : '' ?>
                        </td>
                        <td class="<?= $estadoTipo ?>">
                            <?= $i['tipo'] ?>
                        </td>
                        <td class="<?= $estadoPlataforma ?>">
                            <?= $i['plataforma'] ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <!-- pasarle los datos al archivo javascript -->
    <script>
        const datos = <?= json_encode($datos, JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <script src="js/buscadorJuegos.js"></script>
</body>

</html>