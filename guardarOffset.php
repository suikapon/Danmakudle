<?php
session_start();
//guardar el offset que manda el js en la sesión para que persista al reiniciar la página
if (isset($_GET['offset']))
{
    $key = $_GET['key'] ?? 'audioOffset';
    // guardar si es diario o no para que no afecte si se juega infinito
    $_SESSION[$key] = (float)$_GET['offset'];
}
?>