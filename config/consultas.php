<?php

function getPersonajes($conexion)
{
    return $conexion->query("SELECT * FROM personajes")->fetchAll(PDO::FETCH_ASSOC);
}

function getPersonajesXDebut($conexion, $desde, $hasta)
{
    return $conexion->query("SELECT * FROM personajes WHERE debut BETWEEN $desde AND $hasta")->fetchAll(PDO::FETCH_ASSOC);
}

function getPersonajesConTemas($conexion)
{
    return $conexion->query("SELECT * FROM personajes WHERE audio IS NOT null")->fetchAll(PDO::FETCH_ASSOC);
}

function getPersonajeXID($conexion, $id)
{
    $stmt = $conexion->prepare("SELECT * FROM personajes WHERE id_personaje = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPersonajeAleatorio($conexion)
{
    return $conexion->query("SELECT * FROM personajes ORDER BY RANDOM() LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}

function getPersonajeAleatorioXDebut($conexion, $desde, $hasta)
{
    return $conexion->query("SELECT * FROM personajes WHERE debut BETWEEN $desde AND $hasta ORDER BY RANDOM() LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}

function getPersonajeConTemaAleatorioXDebut($conexion, $desde, $hasta)
{
    return $conexion->query("SELECT * FROM personajes WHERE debut BETWEEN $desde AND $hasta AND audio IS NOT NULL ORDER BY RANDOM() LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}

function getPersonajeConTemaAleatorio($conexion)
{
    return $conexion->query("SELECT * FROM personajes WHERE audio IS NOT NULL ORDER BY RANDOM() LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}

function getNombreJuego($conexion, $debut)
{
    $stmt = $conexion->prepare("SELECT nombre FROM juegos WHERE id = ?");
    $stmt->execute([$debut]);
    return $stmt->fetchColumn();
}

function getJuegos($conexion)
{
    return $conexion->query("SELECT * FROM juegos")->fetchAll(PDO::FETCH_ASSOC);
}


function getJuegoAleatorio($conexion)
{
    return $conexion->query("SELECT * FROM juegos ORDER BY RANDOM() LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}

function getJuegosXDebut($conexion, $desde, $hasta)
{
    return $conexion->query("SELECT * FROM juegos WHERE id BETWEEN $desde AND $hasta")->fetchAll(PDO::FETCH_ASSOC);
}

function getJuegoAleatorioXDebut($conexion, $desde, $hasta)
{
    return $conexion->query("SELECT * FROM juegos WHERE id BETWEEN $desde AND $hasta ORDER BY RANDOM() LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}

function existeUsername($conexion, $username, $idExcluir = null)
{
    if ($idExcluir) {
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE username = ? AND id_usuario != ?");
        $stmt->execute([$username, $idExcluir]);
    } else {
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
    }

    return $stmt->fetchColumn() ? true : false;
}

function existeCorreo($conexion, $correo, $idExcluir = null)
{
    if ($idExcluir) {
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
        $stmt->execute([$correo, $idExcluir]);
    } else {
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->execute([$correo]);
    }

    return $stmt->fetchColumn() ? true : false;
}

function insertarUsuario($conexion, $username, $email, $password, $rol, $avatar)
{
    $stmt = $conexion->prepare("INSERT INTO usuarios (username, email, password, rol, avatar) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$username, $email, $password, $rol, $avatar]);
}

function crearEstadisticas($conexion, $id_usuario)
{
    $stmt = $conexion->prepare("INSERT INTO estadisticas_usuario (id_usuario, partidas_jugadas, partidas_ganadas, racha_actual, racha_max, puntos) VALUES (?, 0, 0, 0, 0, 0)");
    return $stmt->execute([$id_usuario]);
}

function actualizarEstadisticas($conexion, $id_usuario, $gano)
{
    $stmt = $conexion->prepare("SELECT * FROM estadisticas_usuario WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    // se va sumando
    $partidas_jugadas = $stats['partidas_jugadas'] + 1;
    $partidas_ganadas = $stats['partidas_ganadas'] + ($gano ? 1 : 0);
    // si no se gana una partida se reinicia el contador de racha a 0
    $racha_actual = $gano ? $stats['racha_actual'] + 1 : 0;
    $racha_max = max($racha_actual, $stats['racha_max']);
    $puntos = $stats['puntos'] + ($gano ? 10 : 0);

    $stmt = $conexion->prepare("UPDATE estadisticas_usuario SET partidas_jugadas=?, partidas_ganadas=?, racha_actual=?, racha_max=?, puntos=? WHERE id_usuario=?");
    return $stmt->execute([$partidas_jugadas, $partidas_ganadas, $racha_actual, $racha_max, $puntos, $id_usuario]);
}

function getRanking($conexion, $orden)
{
    $stmt = $conexion->prepare("
        SELECT u.username, u.avatar, e.puntos, e.racha_actual, e.partidas_jugadas, e.partidas_ganadas, e.racha_max
        FROM estadisticas_usuario e
        JOIN usuarios u ON e.id_usuario = u.id_usuario
        ORDER BY e.$orden DESC
        LIMIT 50");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// obtener el personaje o juego del día para un modo
function getElementoDiario($conexion, $hoy, $modo)
{
    $stmt = $conexion->prepare("SELECT * FROM elemento_diario WHERE fecha = ? AND modo = ?");
    $stmt->execute([$hoy, $modo]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// guardar el elemento del día para un modo
function insertarElementoDiario($conexion, $hoy, $modo, $id_elemento)
{
    $stmt = $conexion->prepare("INSERT INTO elemento_diario (fecha, modo, id_elemento) VALUES (?, ?, ?)");
    return $stmt->execute([$hoy, $modo, $id_elemento]);
}

// registrar un intento del usuario
function insertarIntentoDiario($conexion, $hoy, $id_usuario, $id_elemento, $modo)
{
    $stmt = $conexion->prepare("INSERT INTO intentos_diario (fecha, id_usuario, id_elemento, modo) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$hoy, $id_usuario, $id_elemento, $modo]);
}

// obtener los intentos del usuario para hoy en un modo
function getIntentosDiario($conexion, $hoy, $id_usuario, $modo)
{
    $stmt = $conexion->prepare("
        SELECT p.* FROM intentos_diario i
        JOIN personajes p ON i.id_elemento = p.id_personaje
        WHERE i.fecha = ? AND i.id_usuario = ? AND i.modo = ?
        ORDER BY i.id ASC
    ");
    $stmt->execute([$hoy, $id_usuario, $modo]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>