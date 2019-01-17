<?php
// достаём id пользователя, запросившего страницу
$data = [ 'id' => $_SESSION['user']['id'] ];
$sql = 'SELECT * FROM users WHERE id = :id';
$user = getRow($pdo, $sql, $data);

// проверяем права доступа пользователя к панели администратора
if(!checkUser('user')){

	// если пользователь не авторизован, переадресуем на страницу авторизации
	header('Location: /login.php');
	exit();
}
else if($user['isAdmin'] != 1){

	// если пользователь не является администратором, выводим сообщение об ошибке
	echo('<h1>Access denied</h1>');
	exit();
} 
else {
	// записываем в сессию администратора
	$_SESSION['admin'] = true;
}
