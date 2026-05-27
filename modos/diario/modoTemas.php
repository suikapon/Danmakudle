<?php
session_start();

$vidas = 6;

require_once '../../config/config.php';
require_once '../../config/consultas.php';
require_once '../../config/funciones.php';
require_once '../../config/dificultad.php';

$hoy = hoyDiario();
$proximoReinicio = proximoReinicio();

// cargamos los personajes con tema de la base de datos dependiendo de la dificultad
$personajes = getPersonajesXDebut($conn, $desde, $hasta);

// buscar el personaje del día
$diario = getElementoDiario($conn, $hoy, 'temas', $dificultad);

if (!$diario) {
    // si no hay personaje para hoy se genera uno aleatorio y se guarda
    $audioAdivinar = getPersonajeConTemaAleatorioXDebut($conn, $desde, $hasta);
    insertarElementoDiario($conn, $hoy, 'temas', $audioAdivinar['id_personaje'], $dificultad);
} else {
    $audioAdivinar = getPersonajeXID($conn, $diario['id_elemento']);
}

// preparar nombres y la imagen de cada personaje para pasárselo al javascript
$datos = [];
foreach ($personajes as $p) {
    $datos[] = ['nombre' => $p['nombre'], 'imagen' => $p['imagen']];
}

// cargar intentos del usuario para hoy
$logueado = isset($_SESSION['id_usuario']);

if ($logueado) {
    $intentos = getIntentosDiario($conn, $hoy, $_SESSION['id_usuario'], 'temas', $dificultad);
} else {
    // si el personaje del día cambió limpiar intentos del invitado
    $idElementoHoy = $diario ? $diario['id_elemento'] : null;
    if (!isset($_SESSION['elementoDiarioTemas']) || $_SESSION['elementoDiarioTemas'] != $idElementoHoy) {
        unset($_SESSION['intentosDiarioTemas']);
        $_SESSION['elementoDiarioTemas'] = $idElementoHoy;
    }
    if (!isset($_SESSION['intentosDiarioTemas'])) $_SESSION['intentosDiarioTemas'] = [];
    $intentos = $_SESSION['intentosDiarioTemas'];
}

// procesar el intento enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['personaje_elegido'])) {
    // comprobar si ya ha sido intentado el personaje
    $pjYaIntentado = false;
    foreach ($intentos as $i) {
        if ($i['nombre'] == $_POST['personaje_elegido']) {
            $pjYaIntentado = true;
            break;
        }
    }
    // agregarlo a los intentos si no está ya intentado
    if (!$pjYaIntentado) {
        foreach ($personajes as $p) {
            if ($p['nombre'] == $_POST['personaje_elegido']) {
                if ($logueado) {
                    // guardar el intento
                    insertarIntentoDiario($conn, $hoy, $_SESSION['id_usuario'], $p['id_personaje'], 'temas', $dificultad);
                } else {
                    $_SESSION['intentosDiarioTemas'][] = $p;
                }
                break;
            }
        }
    }

    // recargar intentos tras insertar
    if ($logueado) {
        $intentos = getIntentosDiario($conn, $hoy, $_SESSION['id_usuario'], 'temas', $dificultad);
    } else {
        $intentos = $_SESSION['intentosDiarioTemas'];
    }

    // redirigir para evitar reenvío del formulario
    header("Location: modoTemas.php?diff=" . $dificultad);
    exit();
}

// calcular vidas a partir de intentos fallidos
// intentosfallidos da los intentos donde el personaje no coincide para contarlos y restar las vidas
$intentosFallidos = array_filter($intentos, fn($i) => $i['id_personaje'] != $audioAdivinar['id_personaje']);
$vidasRestantes = $vidas - count($intentosFallidos);

$gano = !empty($intentos) && end($intentos)['id_personaje'] == $audioAdivinar['id_personaje'];
$perdio = $vidasRestantes <= 0 && !$gano;

// leer el offset guardado en bd si existe
$audioOffset = $diario ? $diario['audio_offset'] : null;

if (($gano || $perdio) && $logueado)
{
    if (!partidaDiariaContada($conn, $hoy, $_SESSION['id_usuario'], 'temas', $dificultad))
    {
        $puntos = calcularPuntos('diario', $dificultad, count($intentosFallidos), $vidas);
        $contarRacha = false; // no cuenta racha en algo que no sea el clásico diario
        actualizarEstadisticas($conn, $_SESSION['id_usuario'], $gano, $puntos, $contarRacha);
        marcarPartidaDiariaContada($conn, $hoy, $_SESSION['id_usuario'], 'temas', $dificultad);
    }
}
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
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include '../../header.php'; ?>

    <main class="container d-flex flex-column align-items-center flex-grow-1">
        <h1 class="text-center mb-4">Adivina el personaje del tema</h1>
        <p class="subtitulo">Se actualiza cada día a las 00:00</p>
        <!-- cuenta atrás para el próximo personaje -->
        <p class="subtitulo">Próximo personaje en: <span id="contador"></span></p>
        <?php botonesDificultad($dificultad); ?>
        <div id="texto-vidas" class="d-flex justify-content-center mb-4">
            <span>Vidas:</span>
            <?php for ($i = 0; $i < $vidasRestantes; $i++): ?>
                <img src="../../img/stars/vida.png" width="20" height="20">
            <?php endfor; ?>
        </div>

        <!-- audio !-->
        <div class="audio">
            <button onclick="reproducir()" class="btn btn-danmaku">Reproducir</button>
            <button onclick="parar()" class="btn btn-danmaku">Parar</button>
        </div>

        <div class="audio">
            <i class="bi bi-volume-up-fill fs-4 align-middle me-2"></i>
            <input type="range" id="vol" min="0" max="100" value="30" style="width:150px; vertical-align:middle;">
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

        <?php if ($perdio):
            revelarPersonaje($audioAdivinar);
        endif; ?>
        <?php if ($gano && $logueado): 
            $fallos = $vidas - $_SESSION['vidasPersonajes'];
            $puntos = calcularPuntos('dirario', $dificultad, $fallos, $vidas);
            revelarPuntos($puntos);
        endif; ?>

        <table class="tabla-intentos">
            <thead>
                <tr>
                    <th>Personaje</th>
                    <th>Nombre</th>
                    <th>Debut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_reverse($intentos) as $i):
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
                            <img src="../../img/pj/<?= $i['imagen'] ?>" width=100 height=100>
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
    <script src="../../js/buscador.js"></script>
    <script src="../../js/contador.js"></script>
    <script>
        iniciarContador(<?= $proximoReinicio ?> * 1000);
    </script>
    <script>
        // ruta del audio del personaje a adivinar
        const audioSrc = '../../media/audio/<?= $audioAdivinar['audio'] ?>';

        //motor de audio del navegador
        const audioCtx = new AudioContext();
        // control de volumen
        let gainNode = audioCtx.createGain();
        gainNode.connect(audioCtx.destination);
        gainNode.gain.value = 0.2;
        // el audio cargado y decodificado 
        let buffer = null;
        // la fuente de audio que está sonando ahora
        let source = null;
        // el segundo por el que empieza el audio
        let startOffset = null;

        // offset guardado en bd si no hay por ser la primera vez que carga hacerlo nulo
        const audioOffset = <?= $audioOffset ?? 'null' ?>;

        // cargar el archivo de audio
        fetch(audioSrc)
            .then(res => res.arrayBuffer())
            .then(data => audioCtx.decodeAudioData(data))
            .then(decoded => {
                // guardar el audio decodificado
                buffer = decoded;
                if (audioOffset === null) {
                    // calcular el maximo segundo desde donde pueda empezar
                    // restando 5 para que quepan 5 segundos antes del final
                    const maxStart = Math.max(0, buffer.duration - 5);
                    startOffset = Math.random() * maxStart;

                    //guardarlo en la bd php para que persista
                    fetch('../../guardarOffsetDiario.php?offset=' + startOffset + '&diff=<?= $dificultad ?>');
                } else
                    startOffset = audioOffset;
            });

        function reproducir() {
            // si no hay audio cargado no hacer nada
            if (!buffer) return;

            // si hay algo reproduciendose parar el anterior antes de empezar
            // (sino empiezan a sonar muchos a la vez)
            if (source) { source.stop(); source = null; }

            // crear una nueva fuente de audio a partir del buffer
            source = audioCtx.createBufferSource();
            source.buffer = buffer;

            // conectar la fuente a los altavoces
            source.connect(gainNode);

            // empieza a sonar ahora (el 0) desde el offset calculado durante 5 segundos
            source.start(0, startOffset, 5);
        }

        function parar() {
            // si hay algo sonando detenerlo
            if (source) { source.stop(); source = null; }
        }

        document.getElementById('vol').oninput = function () {
            gainNode.gain.value = this.value / 100;
        };
    </script>
</body>

</html>