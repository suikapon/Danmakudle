<?php
session_start();
if (isset($_GET['offset']))
    $_SESSION['audioOffset'] = (float)$_GET['offset'];