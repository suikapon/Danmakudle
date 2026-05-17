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

?>