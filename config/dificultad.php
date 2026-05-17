<?php
$dificultad = isset($_GET['diff']) ? $_GET['diff'] : 'normal';

switch ($dificultad) {
    case 'facil':
        $vidas = 8;
        $desde = 1;
        $hasta = 10;
        break;
    case 'normal':
        $vidas = 7;
        $desde = 1;
        $hasta = 15;
        break;
    case 'dificil':
        $vidas = 6;
        $desde = 1;
        $hasta = 20;
        break;
    default:
        $dificultad = 'normal';
        $vidas = 7;
        $desde = 1;
        $hasta = 15;
        break;
}

function botonesDificultad($dificultad)
{
    echo '
    <div style="margin-bottom: 15px; text-align: center;">
        <a href="?diff=facil&reset=1" style="margin-right: 10px; font-weight: ' . ($dificultad == "facil" ? "bold" : "normal") . '">Fácil</a>
        <a href="?diff=normal&reset=1" style="margin-right: 10px; font-weight: ' . ($dificultad == "normal" ? "bold" : "normal") . '">Normal</a>
        <a href="?diff=dificil&reset=1" style="font-weight: ' . ($dificultad == "dificil" ? "bold" : "normal") . '">Difícil</a>
    </div>
    <p class="text-center">Modo actual: <strong style="text-transform: uppercase;">' . $dificultad . '</strong></p>
    ';
}