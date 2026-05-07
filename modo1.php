<?php
session_start();
// reiniciar los intentos y el personaje para pruebas por ahora
if (isset($_GET['reset']))
{
    unset($_SESSION['intentos']);
    unset($_SESSION['pjAdivinar']);
    header('Location: modo1.php');
    exit();
}

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

// procesar el intento enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['personaje_elegido']))
{
    // comprobar si ya ha sido intentado el personaje
    $pjYaIntentado = false;
    foreach ($_SESSION['intentos'] as $i)
    {
        if ($i['nombre'] == $_POST['personaje_elegido'])
        {
            $pjYaIntentado = true;
            break;
        }
    }
    // agregarlo a los intentos si no está ya intentado
    if (!$pjYaIntentado)
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

    <!--botones para reiniciar intentos y el personaje-->
    <a href="?reset=todo">cambiar personaje</a>

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
    
    <!--guardar estado de las coincidencias de los juegos para elegir el juego-->
    <!--array invertido porque se más cómodo que el último intento salga arriba del todo-->
    <?php foreach(array_reverse($intentos) as $i):

        $idIntento = $i['id_personaje'];
        $idSecreto = $pjAdivinar['id_personaje'];

        // nombre
        $estadoNombre=($idIntento==$idSecreto)? 'verde':'rojo';

        // apariciones
        if (mismasApariciones($conn,$idIntento,$idSecreto))
        {
            $estadoApariciones='verde';
        }
        elseif (coincidenApariciones($conn,$idIntento,$idSecreto))
        {
            $estadoApariciones='naranja';
        }
        else
        {
            $estadoApariciones='rojo';
        }

        // especie
        $estadoEspecie = ($i['especie_normalizada']==$pjAdivinar['especie_normalizada'])?'verde':'rojo';

        // ocupacion
        $estadoOcupacion = ($i['ocupacion']==$pjAdivinar['ocupacion'])?'verde':'rojo';

        // ubicacion
        $estadoUbicacion = ($i['ubicacion']==$pjAdivinar['ubicacion'])?'verde':'rojo';
    ?>

<div class="fila-intento">
    <!-- reutilizado el estado del nombre porque no veo necesario comparara la imagen-->
    <div class="caja <?=$estadoNombre?>">
        <img src="<?=$i['imagen']?>" width=100 height=100>
    </div>
    <div class="caja <?= $estadoNombre ?>">
        <?= $i['nombre'] ?>
    </div>

    <div class="caja <?= $estadoApariciones ?>">
        <?php foreach (getApariciones($conn,$idIntento) as $j): ?>
            <span><?=$j?></span><br>
        <?php endforeach; ?>
    </div>

    <div class="caja <?= $estadoEspecie ?>">
        <?= $i['especie_normalizada'] ?>
    </div>

    <div class="caja <?= $estadoOcupacion ?>">
        <?= $i['ocupacion'] ?>
    </div>
    
    <div class="caja <?= $estadoUbicacion ?>">
        <?= $i['ubicacion'] ?>
    </div>
</div>

<?php endforeach; ?>

    <!-- pasarle los datos al archivo javascript -->
    <script>
        const datos = <?= json_encode($datos,JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <script src="js/buscador.js"></script>
</body>
</html>