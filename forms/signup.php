<?php 
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

if(isFormSend()) {

	// для удобства сохраняю массив _POST в переменную
	$form = $_POST;

	// фильтрация данных
	$login = filter($form['login']);
	$email = filter($form['email']);
	$password = filter($form['password']);
	$password_confirm = filter($form['password_confirm']);

	// валидация и сбор ошибок в массив
	$required = ['login', 'email', 'password'];
	$errors = [];
	$isError = false;

	foreach ($form as $key => $value) {
		if(in_array($key, $required)){
			// проверка заполненности полей
			if(!checkRequired($required, $key, $value)){
				$errors[$key] = 'This value is too short';
				$isError = true;
			}
			else if($key == 'email'){
				// проверка формата емейла
				if(!checkEmail($email)){
					$errors[$key] = 'Uncorrect Email format';
					$isError = true;
				}
			}
		}
	}

	if(!$isError) {
		// если ошибок нет, проверяем наличие в базе данных пользователя с таким логином
		$data = [ 'login' => $login ];
		$sql = 'SELECT * FROM users WHERE login = :login';
		$user = getRow($pdo, $sql, $data);
		if($user){
			$errors['login'] = 'This login is already used';
		}

		// или емейлом
		$data = [ 'email' => $email ];
		$sql = 'SELECT * FROM users WHERE email = :email';
		$user = getRow($pdo, $sql, $data);
		if($user){
			$errors['email'] = 'This email is already used';
		}
		// если все в порядке, проверяем, правильно ли во второй раз пользователь ввел пароль
		else if ($password_confirm != $password) {
			$errors['password'] = 'Passwords are not matched';
		}
	}

	// выводим ошибки, если они есть
	if (!empty($errors)) showErrors($errors);

	// хэширование пароля
	$password = password_hash($password, PASSWORD_DEFAULT);

	// добавление пользователя в базу данных и вывод сообщения об успешной регистрации
	$data = 
	[
		'login' => $login, 
		'email' => $email, 
		'password' => $password
	];

	$sql = 'INSERT INTO users(login, email, password) VALUES(:login, :email, :password)';
	execute($pdo, $sql, $data);

	echo('success');
}
