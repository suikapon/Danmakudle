<?php

function getPersonajes($conexion) 
{
    return $conexion->query("SELECT * FROM personajes");
}

function getPersonajeXID($conexion, $id)
{
    $stmt = $conexion->prepare("SELECT * FROM personajes WHERE id_personaje=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getPersonajeAleatorio($conexion)
{
    $stmt = $conexion->prepare("SELECT * FROM personajes ORDER BY RAND() LIMIT 1");
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

?>