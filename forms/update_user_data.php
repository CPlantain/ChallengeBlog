<?php 
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверяем, отправлена ли форма
if(isFormSend()) {
	// для удобства сохраняю массив _POST в переменную
	$form = $_POST;

	// фильтрация данных
	$id = $form['user_id'];
	$login = filter($form['login']);
	$email = filter($form['email']);
	$password = filter($form['password']);

	$data = [ 'id' => $id ];
	$sql = 'SELECT * FROM users WHERE id = :id'; 
	$user = getRow($pdo, $sql, $data);

	// проверяем права доступа пользователя
	if(!checkAuthor($user['id'])){
		// в случае отказа адресуем на главную
		header('Location: /');
		exit();
	}
	else {
		// валидация и сбор ошибок в массив
		$required = ['login', 'email'];
		$errors = [];
		$isError = false;

		foreach ($form as $key => $value) {
			if(in_array($key, $required)){

				// проверка заполненности полей
				if(!checkRequired($required, $key, $value)){
					$errors[$key] = 'This value is too short';
					$isError = true;
				}
				// проверка формата емейла
				else if($key == 'email'){
					if(!checkEmail($email)){
						$errors[$key] = 'Uncorrect Email format';
						$isError = true;
					}
				}
			}
		}

		if(!$isError) {
			// если ошибок нет, проверяем, изменил ли что-нибудь пользователь
			if($login == $user['login'] && $email == $user['email']){
				$errors['login and email'] = 'You did not update anything';
			}
			else {
				// если изменил, проверяем, не занят ли уже новый емейл
				$data = [ 'email' => $email ];
				$sql = 'SELECT * FROM users WHERE email = :email';
				$user_email = getRow($pdo, $sql, $data);
				
				if(($user_email != null) && ($email != $user['email'])){
					$errors['email'] = 'This email is already used';
				}

				// или логин
				$data = [ 'login' => $login ];
				$sql = 'SELECT * FROM users WHERE login = :login';
				$user_login = getRow($pdo, $sql, $data);
		
				if(($user_login != null) && ($login != $user['login'])){
					$errors['login'] = 'This login is already used';
				}

				// если не занят, то проверяем, правильно ли введен пароль
				else if (!password_verify($password, $user['password'])) {
					$errors['password'] = 'Wrong password';
				}
			}		
		}

				
		// если есть ошибки, выводим их
		if (!empty($errors)) showErrors($errors);

		//изменение данных пользователя и вывод сообщения об этом
		$data = 
		[
			'login' => $login, 
			'email' => $email, 
			'id' => $id
		];

		$sql = 'UPDATE users SET login = :login, email = :email WHERE id = :id';
		execute($pdo, $sql, $data);

		echo('success');
	}
}
