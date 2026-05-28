<?php
session_start();
//session_destroy();

require_once '../../config/dificultad.php';
$vidas = 6;

require_once '../../config/config.php';
require_once '../../config/consultas.php';
require_once '../../config/funciones.php';

$hoy = hoyDiario();
$proximoReinicio = proximoReinicio();

$personajes = getPersonajesXDebut($conn, $desde, $hasta);

// buscar el personaje del día
$diario = getElementoDiario($conn, $hoy, 'siluetas', $dificultad);

if (!$diario) {
    // si no hay personaje para hoy se genera uno aleatorio y se guarda
    $silAdivinar = getPersonajeAleatorioXDebut($conn, $desde, $hasta);
    insertarElementoDiario($conn, $hoy, 'siluetas', $silAdivinar['id_personaje'], $dificultad);
} else {
    $silAdivinar = getPersonajeXID($conn, $diario['id_elemento']);
}

// preparar nombres y la imagen de cada personaje para pasárselo al javascript
$datos = [];
foreach ($personajes as $p) {
    $datos[] = ['nombre' => $p['nombre'], 'imagen' => $p['imagen']];
}

// cargar intentos del usuario para hoy
$logueado = isset($_SESSION['id_usuario']);

if ($logueado) {
    $intentos = getIntentosDiario($conn, $hoy, $_SESSION['id_usuario'], 'siluetas', $dificultad);
} else {
    // si el personaje del día cambió limpiar intentos del invitado
    $idElementoHoy = $diario ? $diario['id_elemento'] : null;
    if (!isset($_SESSION['elementoDiarioSiluetas']) || $_SESSION['elementoDiarioSiluetas'] != $idElementoHoy) {
        unset($_SESSION['intentosDiarioSiluetas']);
        $_SESSION['elementoDiarioSiluetas'] = $idElementoHoy;
    }
    if (!isset($_SESSION['intentosDiarioSiluetas'])) $_SESSION['intentosDiarioSiluetas'] = [];
    $intentos = $_SESSION['intentosDiarioSiluetas'];
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
                    insertarIntentoDiario($conn, $hoy, $_SESSION['id_usuario'], $p['id_personaje'], 'siluetas', $dificultad);
                } else {
                    $_SESSION['intentosDiarioSiluetas'][] = $p;
                }
                break;
            }
        }
    }

    // recargar intentos tras insertar
    if ($logueado) {
        $intentos = getIntentosDiario($conn, $hoy, $_SESSION['id_usuario'], 'siluetas', $dificultad);
    } else {
        $intentos = $_SESSION['intentosDiarioSiluetas'];
    }

    // redirigir para evitar reenvío del formulario
    header("Location: modoSiluetas.php?diff=" . $dificultad);
    exit();
}

// calcular vidas a partir de intentos fallidos
// intentosfallidos da los intentos donde el personaje no coincide para contarlos y restar las vidas
$intentosFallidos = array_filter($intentos, fn($i) => $i['id_personaje'] != $silAdivinar['id_personaje']);
$vidasRestantes = $vidas - count($intentosFallidos);

$gano = !empty($intentos) && end($intentos)['id_personaje'] == $silAdivinar['id_personaje'];
$perdio = $vidasRestantes <= 0 && !$gano;

if (($gano || $perdio) && $logueado)
{
    if (!partidaDiariaContada($conn, $hoy, $_SESSION['id_usuario'], 'siluetas', $dificultad))
    {
        $puntos = calcularPuntos('diario', $dificultad, count($intentosFallidos), $vidas);
        $contarRacha = false; // no cuenta racha en algo que no sea el clásico diario
        actualizarEstadisticas($conn, $_SESSION['id_usuario'], $gano, $puntos, $contarRacha);
        marcarPartidaDiariaContada($conn, $hoy, $_SESSION['id_usuario'], 'siluetas', $dificultad);
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
        <h1 class="text-center mb-4">Adivina el personaje de la silueta</h1>
        <p class="subtitulo">Se actualiza cada día a las 00:00</p>
        <!-- cuenta atrás para el próximo personaje -->
        <p class="subtitulo">Próximo personaje en: <span id="contador"></span></p>
        <?php botonesDificultad($dificultad);?>
        <div id="texto-vidas" class="d-flex justify-content-center mb-4">
            <span>Vidas:</span>
            <?php
            for ($i = 0; $i < $vidasRestantes; $i++): ?>
                <img src="../../img/stars/vida.png" width="20" height="20">
            <?php endfor; ?>
        </div>
        <img src="../../img/pj/<?= $silAdivinar['imagen'] ?>" class="<?= ($gano||$perdio) ? '' : 'silueta' ?> img-fluid"
            style="max-width: 300px; height: auto;">

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
            revelarPersonaje($silAdivinar);
        endif;
        ?>
        <?php if ($gano && $logueado): 
            $fallos = count($intentosFallidos);
            $puntos = calcularPuntos('diario', $dificultad, $fallos, $vidas);
            revelarPuntos($puntos);
        endif; ?>

        <div class="table-responsive w-100" style="max-width: 500px;">
            <table class="tabla-intentos">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_reverse($intentos) as $i):
                        // almacenar el color de cada campo como un estado para que se vea en las comparaciones en el juego usando las clases !!
                        $idIntento = $i['id_personaje'];
                        $idSecreto = $silAdivinar['id_personaje'];

                        // nombre
                        $estadoNombre = estadoSimple($i, $silAdivinar, 'id_personaje');
                        ?>

                        <tr>
                            <td class="<?= $estadoNombre ?>">
                                <img src="../../img/pj/<?= $i['imagen'] ?>" class="img-fluid" style="max-width: 60px; height: auto;">
                            </td>

                            <td class="<?= $estadoNombre ?>">
                                <?= $i['nombre'] ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
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
</body>

</html>