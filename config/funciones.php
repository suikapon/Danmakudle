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
?>