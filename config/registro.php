<?php
require_once 'config.php';
require_once 'consultas.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = hash('sha256',$_POST['password']);
// imagen predeterminada
$avatar = 'https://res.cloudinary.com/dv8vqn76n/image/upload/v1779032144/wriggle_lqaaby.jpg';

if (existeUsername($conn, $username)) {
    header('Location: ../registro.php?error=username');
    exit;
}

require_once '../vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => getenv('CLOUDINARY_NAME'),
        'api_key' => getenv('CLOUDINARY_KEY'),
        'api_secret' => getenv('CLOUDINARY_SECRET'),
    ]
]);

if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
    $extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($extension, $permitidas)) {
        $resultado = $cloudinary->uploadApi()->upload($_FILES['avatar']['tmp_name']);
        $avatar = $resultado['secure_url'];
    }
}

insertarUsuario($conn, $username, $email, $password, 'normal', $avatar);

$id = $conn->lastInsertId();
crearEstadisticas($conn, $id);

header('Location: ../login.php?registro=1');
exit;