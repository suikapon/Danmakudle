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

function mismasApariciones($conexion, $id1, $id2)
{
    $sql = "SELECT aparicion FROM apariciones WHERE id_personaje = ?";

    $stmt = $conexion->prepare($sql);

    // apariciones personaje 1
    $stmt->execute([$id1]);
    $a1 = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // apariciones personaje 2
    $stmt->execute([$id2]);
    $a2 = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // ordenar arrays para compararlos bien
    sort($a1);
    sort($a2);

    // true si son exactamente iguales
    return $a1 === $a2;
}

function coincidenApariciones($conexion,$id1,$id2)
{
    //busca si dos personajes comparten al menos una aparición
    $sql = "
        SELECT 1
        FROM apariciones a1
        INNER JOIN apariciones a2
            ON a1.aparicion = a2.aparicion
        WHERE a1.id_personaje = ?
          AND a2.id_personaje = ?
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id1, $id2]);

    return $stmt->fetch() !== false;  //si encontró algo devuelve true, si no false
}

function getApariciones($conexion, $idPersonaje)
{
    $stmt = $conexion->prepare("SELECT aparicion FROM apariciones WHERE id_personaje = ?");
    $stmt->execute([$idPersonaje]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

?>