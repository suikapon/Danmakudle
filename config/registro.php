<?php
require_once 'config.php';
require_once 'consultas.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$avatar = 'wriggle.jpg';

if (existeUsername($conn, $username)) {
    header('Location: ../registro.php?error=username');
    exit;
}

if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
    $extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($extension, $permitidas)) {
        $nombreArchivo = uniqid('avatar_') . '.' . $extension;
        move_uploaded_file($_FILES['avatar']['tmp_name'], '../img/avatares/' . $nombreArchivo);
        $avatar = $nombreArchivo;
    }
}

insertarUsuario($conn, $username, $email, $password, 'normal', $avatar);
header('Location: ../login.php?registro=1');
exit;