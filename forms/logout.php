<?php

// очищаем сессию пользователя, чтобы он мог выйти из своей учётной записи
require_once "../config.php";
unset($_SESSION['user']);

header('Location: ../index.php');
?>