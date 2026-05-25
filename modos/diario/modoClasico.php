<?php
session_start();

$vidas = 6;

require_once '../../config/config.php';
require_once '../../config/consultas.php';
require_once '../../config/funciones.php';
require_once '../../config/dificultad.php';

$hoy = hoyDiario();
$proximoReinicio = proximoReinicio();

// cargamos todos los personajes
$personajes = getPersonajesXDebut($conn, $desde, $hasta);

// buscar el personaje del día
$diario = getElementoDiario($conn, $hoy, 'clasico', $dificultad);


if (!$diario) {
    // si no hay personaje para hoy se genera uno aleatorio y se guarda
    $pjAdivinar = getPersonajeAleatorioXDebut($conn, $desde, $hasta);
    insertarElementoDiario($conn, $hoy, 'clasico', $pjAdivinar['id_personaje'], $dificultad);
} else {
    $pjAdivinar = getPersonajeXID($conn, $diario['id_elemento']);
}

// preparar nombres y la imagen de cada personaje para pasárselo al javascript
$datos = [];
foreach ($personajes as $p) {
    $datos[] = ['nombre' => $p['nombre'], 'imagen' => $p['imagen']];
}

// cargar intentos del usuario para hoy
$logeado = isset($_SESSION['id_usuario']);

if ($logeado) {
    $intentos = getIntentosDiario($conn, $hoy, $_SESSION['id_usuario'], 'clasico', $dificultad);
} else {
    if (!isset($_SESSION['intentosDiario'])) $_SESSION['intentosDiario'] = [];
    $intentos = $_SESSION['intentosDiario'];
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
                if ($logeado) {
                    // guardar el intento
                    insertarIntentoDiario($conn, $hoy, $_SESSION['id_usuario'], $p['id_personaje'], 'clasico',$dificultad);
                } else {
                    $_SESSION['intentosDiario'][] = $p;
                }
                break;
            }
        }
        
        // redirigir para evitar reenvío del formulario
        header("Location: modoClasico.php?diff=" . $dificultad);
        exit();
    }

    // recargar intentos tras insertar
    if ($logeado) {
        $intentos = getIntentosDiario($conn, $hoy, $_SESSION['id_usuario'], 'clasico',$dificultad);
    } else {
        $intentos = $_SESSION['intentosDiario'];
    }
}

// calcular vidas a partir de intentos fallidos
// intentosfallidos da los intentos donde el personaje no coincide para contarlos y restar las vidas
$intentosFallidos = array_filter($intentos, fn($i) => $i['id_personaje']!=$pjAdivinar['id_personaje']);
$vidasRestantes = $vidas-count($intentosFallidos);

$gano = !empty($intentos) && end($intentos)['id_personaje'] == $pjAdivinar['id_personaje'];
$perdio = $vidasRestantes <= 0 && !$gano;

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

        <h1>Adivina el personaje del día</h1>
        <p class="subtitulo">Se actualiza cada día a las 12:00</p>

        <!-- cuenta atrás para el próximo personaje -->
        <p class="subtitulo">Próximo personaje en: <span id="contador"></span></p>

        <div id="texto-vidas">
            <span>Vidas:</span>
            <?php
            for ($i = 0; $i < $vidasRestantes; $i++): ?>
                <img src="../../img/stars/vida.png" width="20" height="20">
            <?php endfor; ?>
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

        <?php if ($perdio):
            revelarPersonaje($pjAdivinar);
        endif;
        ?>

        <table class="tabla-intentos">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Debut</th>
                    <th>Stage</th>
                    <th>Especie</th>
                    <th>Ubicación</th>
                    <th>Jugable</th>
                </tr>
            </thead>
            <tbody>

                <!--guardar estado de las coincidencias de los juegos para elegir el juego-->
                <!--array invertido porque se más cómodo que el último intento salga arriba del todo-->
                <?php foreach (array_reverse($intentos) as $i):
                    // almacenar el color de cada campo como un estado para que se vea en las comparaciones en el juego usando las clases !!
                    $idIntento = $i['id_personaje'];
                    $idSecreto = $pjAdivinar['id_personaje'];

                    // nombre
                    $estadoNombre = estadoSimple($i, $pjAdivinar, 'id_personaje');

                    //debut
                    $resultadoDebut = compararValor((float) $i['debut'], (float) $pjAdivinar['debut']);
                    $estadoDebut = ($resultadoDebut == 'verde') ? 'verde' : 'rojo';

                    // stage
                    $resultadoStage = compararValor(ordenarStage($i['stage']), ordenarStage($pjAdivinar['stage']));
                    $estadoStage = ($resultadoStage == 'verde') ? 'verde' : 'rojo';

                    // especie
                    $estadoEspecie = estadoEspecie($i, $pjAdivinar);

                    // ubicacion
                    $estadoUbicacion = estadoSimple($i, $pjAdivinar, 'ubicacion');

                    // jugable
                    $estadoJugable = estadoSimple($i, $pjAdivinar, 'jugable');
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

                        <td class="<?= $estadoStage ?>">
                            <?= $i['stage'] ?>
                            <?= $resultadoStage !== 'verde' ? $resultadoStage : '' ?>
                        </td>

                        <td class="<?= $estadoEspecie ?>">
                            <?= $i['especie'] ?>
                        </td>

                        <td class="<?= $estadoUbicacion ?>">
                            <?= $i['ubicacion'] ?>
                        </td>

                        <td class="<?= $estadoJugable ?>">
                            <?= ($i['jugable']) ? 'Sí' : 'No' ?>
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
        iniciarContador(<?=$proximoReinicio?>*1000);
    </script>
</body>

</html>