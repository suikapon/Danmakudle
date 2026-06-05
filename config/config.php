<?php
$servername = "dpg-d8has937uimc73cq3drg-a.frankfurt-postgres.render.com";
$username = "danmakudle_2gcv_user";
$password = "AtbuvfVkXoenHFJ8MCA4tgY5QeulXzMy";
$dbname = "danmakudle_2gcv";

try {
    $conn = new PDO("pgsql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Falló: " . $e->getMessage());
}
?>