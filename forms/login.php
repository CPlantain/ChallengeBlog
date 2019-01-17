<?php 
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

if(isFormSend()) {

	// для удобства сохраняю массив _POST в переменную
	$form = $_POST;

	// фильтрация данных и хэширование пароля
	$login = filter($form['login']);
	$password = filter($form['password']);

	// валидация и сбор ошибок в массив
	$required = ['login', 'password'];
	$errors = [];
	$isError = false;

	foreach ($form as $key => $value) {
		if(in_array($key, $required)){
			// проверка заполненности полей
			if(!checkRequired($required, $key, $value)){
				$errors[$key] = 'This value is too short';
				$isError = true;
			} 
		}
	} 

	if(!$isError){
		// если ошибок нет, проверяем, есть ли такой логин в базе данных
		$data = [ 'login' => $login ];
		$sql = 'SELECT id, password FROM users WHERE login = :login';
		$user = getRow($pdo, $sql, $data);
		
		if($user['id'] <= 0) {
			$errors['login'] = 'Such login does not exist';
		} 
		// если есть, проверяем, правильно ли введен пароль
		else {
			if (!password_verify($password, $user['password'])) {
				$errors['password'] = 'Wrong password';
			}
		}

	}

	// если есть ошибки, выводим их
	if (!empty($errors)) showErrors($errors);
	
	// получение пользователя из базы данных и вывод сообщения об успешной авторизации
	$data = [ 'login' => $login ];
	$sql = 'SELECT * FROM users WHERE login = :login';
	
	// записываем пользователя в сессию
	$_SESSION['user'] = getRow($pdo, $sql, $data);
	
	echo('success');
}

