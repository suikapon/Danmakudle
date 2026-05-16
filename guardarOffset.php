<?php
session_start();
//guardar el offset que manda el js en la sesión para que persista al reiniciar la página
if (isset($_GET['offset']))
    $_SESSION['audioOffset'] = (float)$_GET['offset'];
?>