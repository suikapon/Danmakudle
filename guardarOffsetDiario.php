<?php
session_start();
require_once 'config/config.php';

if (isset($_GET['offset']) && isset($_GET['diff'])) {
    $hoy = date('Y-m-d');
    $stmt = $conn->prepare("UPDATE elemento_diario SET audio_offset = ? WHERE fecha = ? AND modo = 'temas' AND dificultad = ?");
    $stmt->execute([$_GET['offset'], $hoy, $_GET['diff']]);
}