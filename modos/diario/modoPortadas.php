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

// cargamos todos los juegos de la base de datos
$juegos = getJuegosXDebut($conn, $desde, $hasta);

// buscar el juego del día
$diario = getElementoDiario($conn, $hoy, 'portadas', $dificultad);

if (!$diario) {
    // si no hay juego para hoy se genera uno aleatorio y se guarda
    $juegoAdivinar = getJuegoAleatorioXDebut($conn, $desde, $hasta);
    insertarElementoDiario($conn, $hoy, 'portadas', $juegoAdivinar['id'], $dificultad);
} else {
    $juegoAdivinar = getJuegoXID($conn, $diario['id_elemento']);
}

// preparar nombres y la imagen de cada juego para pasárselo al javascript
$datos = [];
foreach ($juegos as $j) {
    $datos[] = ['nombre' => $j['nombre'], 'imagen' => $j['imagen'], 'id' => $j['id']];
}

// cargar intentos del usuario para hoy
$logueado = isset($_SESSION['id_usuario']);

if ($logueado) {
    $intentos = getIntentosDiarioJuegos($conn, $hoy, $_SESSION['id_usuario'], 'portadas', $dificultad);
} else {
    // si el juego del día cambió limpiar intentos del invitado
    $idElementoHoy = $diario ? $diario['id_elemento'] : null;
    if (!isset($_SESSION['elementoDiarioPortadas']) || $_SESSION['elementoDiarioPortadas'] != $idElementoHoy) {
        unset($_SESSION['intentosDiarioPortadas']);
        $_SESSION['elementoDiarioPortadas'] = $idElementoHoy;
    }
    if (!isset($_SESSION['intentosDiarioPortadas'])) $_SESSION['intentosDiarioPortadas'] = [];
    $intentos = $_SESSION['intentosDiarioPortadas'];
}

// procesar el intento enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['juego_elegido'])) {
    // comprobar si ya ha sido intentado el juego
    $juegoYaIntentado = false;
    foreach ($intentos as $i) {
        if ($i['nombre'] == $_POST['juego_elegido']) {
            $juegoYaIntentado = true;
            break;
        }
    }
    // agregarlo a los intentos si no está ya intentado
    if (!$juegoYaIntentado) {
        foreach ($juegos as $j) {
            if ($j['nombre'] == $_POST['juego_elegido']) {
                if ($logueado) {
                    // guardar el intento
                    insertarIntentoDiario($conn, $hoy, $_SESSION['id_usuario'], $j['id'], 'portadas', $dificultad);
                } else {
                    $_SESSION['intentosDiarioPortadas'][] = $j;
                }
                break;
            }
        }
    }

    // recargar intentos tras insertar
    if ($logueado) {
        $intentos = getIntentosDiarioJuegos($conn, $hoy, $_SESSION['id_usuario'], 'portadas', $dificultad);
    } else {
        $intentos = $_SESSION['intentosDiarioPortadas'];
    }

    // redirigir para evitar reenvío del formulario
    header("Location: modoPortadas.php?diff=" . $dificultad);
    exit();

}

// calcular vidas a partir de intentos fallidos
// intentosfallidos da los intentos donde el juego no coincide para contarlos y restar las vidas
$intentosFallidos = array_filter($intentos, fn($i) => $i['id'] != $juegoAdivinar['id']);
$vidasRestantes = $vidas - count($intentosFallidos);

$gano = !empty($intentos) && end($intentos)['id'] == $juegoAdivinar['id'];
$perdio = $vidasRestantes <= 0 && !$gano;

//nivel de blur según vidas restantes a menos vidas más se ve la imagen
$blur = $vidasRestantes * 5;

if (($gano || $perdio) && $logueado)
{
    if (!partidaDiariaContada($conn, $hoy, $_SESSION['id_usuario'], 'portadas', $dificultad))
    {
        $puntos = calcularPuntos('diario', $dificultad, count($intentosFallidos), $vidas);
        $contarRacha = false; // no cuenta racha en algo que no sea el clásico diario
        actualizarEstadisticas($conn, $_SESSION['id_usuario'], $gano, $puntos, $contarRacha);
        marcarPartidaDiariaContada($conn, $hoy, $_SESSION['id_usuario'], 'portadas', $dificultad);
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

        <h1 class="text-center mb-4">Adivina el juego por la portada</h1>
        <p class="subtitulo">Se actualiza cada día a las 00:00</p>

        <!-- cuenta atrás para el próximo juego -->
        <p class="subtitulo">Próximo juego en: <span id="contador"></span></p>

        <?php botonesDificultad($dificultad);?>
        <div id="texto-vidas" class="d-flex justify-content-center mb-4">
            <span>Vidas:</span>
            <?php for ($i = 0; $i < $vidasRestantes; $i++): ?>
                <img src="../../img/stars/vida.png" width="20" height="20">
            <?php endfor; ?>
        </div>

        <!--portada con blur desaparece al ganar o perder -->
        <img src="../../img/juegos/<?= $juegoAdivinar['imagen'] ?>" class="portada"
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
        
        <?php if ($gano && $logueado): 
            $fallos = count($intentosFallidos);
            $puntos = calcularPuntos('diario', $dificultad, $fallos, $vidas);
            revelarPuntos($puntos);
        endif; ?>
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
                <?php foreach (array_reverse($intentos) as $i):
                    // almacenar el color de cada campo como un estado para que se vea en las comparaciones en el juego usando las clases !!
                    $estadoNombre = estadoSimple($i, $juegoAdivinar, 'id');

                    // año
                    $resultadoAño = compararValor((int) $i['ano'], (int) $juegoAdivinar['ano']);
                    $estadoAño = ($resultadoAño == 'verde') ? 'verde' : 'rojo';

                    // tipo
                    $estadoTipo = estadoSimple($i, $juegoAdivinar, 'tipo');

                    // plataforma
                    $estadoPlataforma = estadoSimple($i, $juegoAdivinar, 'plataforma');
                    ?>
                    <tr>
                        <td class="<?= $estadoNombre ?>">
                            <img src="../../img/juegos/<?= $i['imagen'] ?>" width=100 height=100>
                        </td>
                        <td class="<?= $estadoNombre ?>">
                            Touhou <?= $i['id'] ?>
                            </br>
                            <?= $i['nombre'] ?>
                        </td>
                        <td class="<?= $estadoAño ?>">
                            <?= $i['ano'] ?>
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
    <script src="../../js/buscadorJuegos.js"></script>
    <script src="../../js/contador.js"></script>
    <script>
        iniciarContador(<?= $proximoReinicio ?> * 1000);
    </script>
</body>

</html>