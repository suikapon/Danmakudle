<?php
session_start();

$vidas = 6;

if (isset($_GET['reset'])) {
    unset($_SESSION['intentosAudio']);
    unset($_SESSION['audioAdivinar']);
    unset($_SESSION['audioOffset']);
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

if (!isset($_SESSION['intentosAudio'])) {
    $_SESSION['intentosAudio'] = [];
}
if (!isset($_SESSION['vidasAudio'])) {
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

// guardar el offset para que no cambie al recargar
if (!isset($_SESSION['audioOffset']))
    $_SESSION['audioOffset'] = null; // el javascript da un offset la primera vez
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

        <!-- audio !-->
        <div class="audio">
            <button onclick="reproducir()">Reproducir</button>
            <button onclick="parar()">Parar</button>
        </div>
        </br>
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
    <script>
        // ruta del audio del personaje a adivinar
        const audioSrc='media/audio/<?= $audioAdivinar['audio']?>';
        
        //motor de audio del navegador
        const audioCtx = new AudioContext();
        // el audio cargado y decodificado 
        let buffer = null;
        // la fuente de audio que está sonando ahora
        let source = null;
        // el segundo por el que empieza el audio
        let startOffset = null;

        // offset guardado en sesión por php, si no hay por ser la primera vez que carga hacerlo nulo
        const audioOffset = <?= $_SESSION['audioOffset'] ?? 'null' ?>;

        // cargar el archivo de audio
        fetch(audioSrc)
            .then(res => res.arrayBuffer())
            .then(data => audioCtx.decodeAudioData(data))
            .then(decoded =>
            {
                // guardar el audio decodificado
                buffer = decoded;
                if (audioOffset===null)
                {
                    // calcular el maximo segundo desde donde pueda empezar
                    // restando 5 para que quepan 5 segundos antes del final
                    const maxStart = Math.max(0, buffer.duration-5);
                    startOffset = Math.random() * maxStart;
                    
                    //guardarlo en la sesión php para que persista
                    fetch('guardarOffset.php?offset='+startOffset);
                } else
                    startOffset = audioOffset;
            });

        function reproducir()
        {
            // si no hay audio cargado no hacer nada
            if (!buffer) return;

            // si hay algo reproduciendose parar el anterior antes de empezar
            // (sino empiezan a sonar muchos a la vez)
            if (source) {source.stop(); source = null;}

            // crear una nueva fuente de audio a partir del buffer
            source = audioCtx.createBufferSource();
            source.buffer = buffer;

            // conectar la fuente a los altavoces
            source.connect(audioCtx.destination);

            // empieza a sonar ahora (el 0) desde el offset calculado durante 5 segundos
            source.start(0,startOffset,5);
        }

        function parar()
        {
            // si hay algo sonando detenerlo
            if (source) {source.stop(); source = null;}
        }
    </script>
</body>

</html>