<?php
session_start();

$vidas = 6;

if (isset($_GET['reset'])) {
    unset($_SESSION['intentosAudio']);
    unset($_SESSION['audioAdivinar']);
    $_SESSION['vidasAudio'] = $vidas;
    header('Location: modo4.php');
    exit();
}

require_once 'config/config.php';
require_once 'config/consultas.php';
require_once 'config/funciones.php';

// cargamos todos los personajes de la base de datos
$personajes = getPersonajes($conn);

// guardamos en la sesión el personaje a adivinar para que no se resetee
if (!isset($_SESSION['audioAdivinar'])) {
    $audioAdivinar = getPersonajeConTemaAleatorio($conn);
    $_SESSION['audioAdivinar'] = $audioAdivinar;
    $_SESSION['intentosAudio'] = [];
    $_SESSION['vidasAudio'] = $vidas;
}
$audioAdivinar = $_SESSION['audioAdivinar'];

// preparar nombres y la imagen de cada personaje para pasárselo al javascript
$datos = [];
foreach ($personajes as $p) {
    $datos[] = ['nombre' => $p['nombre'], 'imagen' => $p['imagen']];
}

// procesar el intento enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['personaje_elegido'])) {
    // comprobar si ya ha sido intentado el personaje
    $pjYaIntentado = false;
    foreach ($_SESSION['intentosAudio'] as $i) {
        if ($i['nombre'] == $_POST['personaje_elegido']) {
            $pjYaIntentado = true;
            break;
        }
    }

    // agregarlo a los intentos si no está ya intentado
    if (!$pjYaIntentado) {
        foreach ($personajes as $p) {
            if ($p['nombre'] == $_POST['personaje_elegido']) {
                $_SESSION['intentosAudio'][] = $p;

                // restar una vida en fallo
                if ($p['id_personaje'] != $audioAdivinar['id_personaje'])
                    $_SESSION['vidasAudio']--;
                break;
            }
        }
    }
}
// recuperar los intentos de la sesión para recorrerlos
$intentosAudio = $_SESSION['intentosAudio'];

$gano = !empty($intentosAudio) && end($intentosAudio)['id_personaje'] == $audioAdivinar['id_personaje'];
$perdio = $_SESSION['vidasAudio'] <= 0 && !$gano;

// rutas audios
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
        <!--botones para reiniciar intentos y el personaje-->
        <a class="text-center mb-4" href="?reset=todo">cambiar personaje</a>

        <h1 class="text-center mb-4">Adivina el personaje del tema</h1>
        <div id="texto-vidas" class="d-flex justify-content-center mb-4">
            <span>Vidas:</span>
            <?php
            for ($i = 0; $i < $_SESSION['vidasAudio']; $i++): ?>
                <img src="img/stars/vida.png" width="20" height="20">
            <?php endfor; ?>
        </div>
        <p>personaje a adivinar:
            <?= $audioAdivinar['nombre'] ?>
        </p>

        <!-- audio !-->
        <div class="audio">
            <audio controls="">
                <source src="media/audio/<?= $audioAdivinar['audio'] ?>">
            </audio>
        </div>

        <?php if (!$gano && !$perdio): ?>
            <form method="POST">
                <div style="position:relative; display:inline-block">
                    <input type="text" id="searchInput" placeholder="Escribe un nombre..." autocomplete="off">
                    <div id="dropdown"
                        style="border:1px solid #ccc; max-height:200px; overflow-y:auto; display:none; position:absolute; width:100%; z-index:999; background:white;">
                    </div>
                    <!-- se manda el hidden para que no se envíen datos erroneos -->
                    <input type="hidden" name="personaje_elegido" id="personajeElegido">
                </div>
                <button type="submit">Adivinar</button>
            </form>
        <?php endif; ?>

        <table class="tabla-intentos">
            <thead>
                <tr>
                    <th>Personaje</th>
                    <th>Nombre</th>
                    <th>Debut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_reverse($intentosAudio) as $i):
                    // almacenar el color de cada campo como un estado para que se vea en las comparaciones en el juego usando las clases !!
                    $idIntento = $i['id_personaje'];
                    $idSecreto = $audioAdivinar['id_personaje'];

                    // nombre
                    $estadoNombre = estadoSimple($i, $audioAdivinar, 'id_personaje');

                    //debut
                    $resultadoDebut = compararValor((float) $i['debut'], (float) $audioAdivinar['debut']);
                    $estadoDebut = ($resultadoDebut == 'verde') ? 'verde' : 'rojo';
                    ?>

                    <tr>
                        <td class="<?= $estadoNombre ?>">
                            <img src="img/pj/<?= $i['imagen'] ?>" width=100 height=100>
                        </td>

                        <td class="<?= $estadoNombre ?>">
                            <?= $i['nombre'] ?>
                        </td>

                        <td class="<?= $estadoDebut ?>">
                            <?= getNombreJuego($conn, $i['debut']) ?>
                            </br>
                            <?= $i['debut'] ?>
                            <!--pone la flecha del estado si no vale verde!-->
                            <?= $resultadoDebut !== 'verde' ? $resultadoDebut : '' ?>
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
    <script src="js/buscador.js"></script>
</body>

</html>