<?php
require_once 'config.php';

$email = $_POST['email'];
$password = $_POST['password'];
$recordar = isset($_POST['recordar']);

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? AND password = ?");
$stmt->execute([$email, $password]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    header("Location: ../login.php?error=1");
    exit;
}

session_start();
$_SESSION['email'] = $email;
$_SESSION['rol'] = $row['rol'];
$_SESSION['username'] = $row['username'];
$_SESSION['id_usuario'] = $row['id_usuario'];
$_SESSION['avatar'] = $row['avatar'];


if ($recordar) {
    setcookie('dm_email', $email, time() + 2500000, '/');
    setcookie('dm_password', $password, time() + 2500000, '/');
    setcookie('dm_recordar', '1', time() + 2500000, '/');
} else {
    setcookie('dm_email', '', time() - 3600, '/');
    setcookie('dm_password', '', time() - 3600, '/');
    setcookie('dm_recordar', '', time() - 3600, '/');
}

header("Location: ../index.php");
exit;