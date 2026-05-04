<?php
session_start();
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
</body>
</html>