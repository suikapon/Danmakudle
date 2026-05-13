<?php
// ordenar los stages
function ordenarStage($stage)
{
    $orden = ['0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'Extra'=>7,'Phantasm'=>8];
    return $orden[$stage] ?? -1;
}
// ^^^^^ devuelve una posición numérica para facilitar la comparación de stages con cualquier otro valor devuelve -1

function compararValor($intento, $secreto)
{
    if($intento==$secreto)
        return 'verde';
    if($intento<$secreto)
        return '↑';
    if($intento>$secreto)
        return '↓';
}

function estadoSimple($intento, $secreto, $campo)
{
    return ($intento[$campo] == $secreto[$campo]) ? 'verde' : 'rojo';
}

// devuelve el estado de color para la especie
function estadoEspecie($intento, $secreto)
{
    if ($intento['especie'] == $secreto['especie'])
        return 'verde';

    $especiesIntento = array_map('trim', explode(',', $intento['especie_normalizada']));
    $especiesSecreto = array_map('trim', explode(',', $secreto['especie_normalizada']));

    if (count(array_intersect($especiesIntento, $especiesSecreto)) > 0)
        return 'naranja';

    return 'rojo';
}

?>