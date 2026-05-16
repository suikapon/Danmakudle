<?php
session_start();
session_destroy();

setcookie('dm_email', '', time() - 3600, '/');
setcookie('dm_password', '', time() - 3600, '/');
setcookie('dm_recordar', '', time() - 3600, '/');

header("Location: ../login.php");
exit;