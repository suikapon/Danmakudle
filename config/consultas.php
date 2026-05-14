<?php

function getPersonajes($conexion) 
{
    return $conexion->query("SELECT * FROM personajes")->fetchAll(PDO::FETCH_ASSOC);
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

function getNombreJuego($conexion, $debut)
{
    $stmt = $conexion->prepare("SELECT nombre FROM juegos WHERE id = ?");
    $stmt->execute([$debut]);
    return $stmt->fetchColumn();
}

function getJuegos($conexion)
{
    $stmt = $conexion->prepare("SELECT * FROM juegos ORDER BY id");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>