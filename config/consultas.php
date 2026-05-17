<?php

function getPersonajes($conexion) 
{
    return $conexion->query("SELECT * FROM personajes")->fetchAll(PDO::FETCH_ASSOC);
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

?>