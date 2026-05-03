<?php
$servername = "dpg-d7jsv5pj2pic73clrgbg-a.frankfurt-postgres.render.com";
$username = "danmakudle_5w7q_user";
$password = "jDkoMkOnHXF0ju66VTTaBPF85f9frDyH";
$dbname = "danmakudle_5w7q";

try {
    $conn = new PDO("pgsql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Falló: " . $e->getMessage());
}
?>