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
    <div class="mb-3 text-center">
        <a href="?diff=facil&reset=1" class="btn btn-sm ' . ($dificultad == "facil" ? "btn-danmaku" : "btn-secondary") . '">Fácil</a>
        <a href="?diff=normal&reset=1" class="btn btn-sm ' . ($dificultad == "normal" ? "btn-danmaku" : "btn-secondary") . '">Normal</a>
        <a href="?diff=dificil&reset=1" class="btn btn-sm ' . ($dificultad == "dificil" ? "btn-danmaku" : "btn-secondary") . '">Difícil</a>
    </div>
    <p class="text-center">Modo actual: <strong style="text-transform: uppercase;">' . $dificultad . '</strong></p>
    ';
}