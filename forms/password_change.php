<?php 
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверяем, была ли отправлена форма
if(isFormSend()) {

	// для удобства сохраняю массив _POST в переменную
	$form = $_POST;

	// фильтрация данных
	$id = $form['user_id'];
	$cur_password = filter($form['cur_password']);
	$new_password = filter($form['new_password']);
	$new_password_confirm = filter($form['new_password_confirm']);

	// получаем данные пользователя из бд
	$data = [ 'id' => $id ];
	$sql = 'SELECT id, password FROM users WHERE id = :id'; 
	$user = getRow($pdo, $sql, $data);

	// проверяем права доступа пользователя
	if(!checkAuthor($user['id'])){
		// в случае отказа адресуем на главную
		header('Location: /');
		exit();
	}
	else {
		// валидация и сбор ошибок в массив
		$required = ['new_password'];
		$errors = [];
		$isError = false;

		foreach ($form as $key => $value) {
			if(in_array($key, $required)){
				
				// проверка заполененности поля
				if(!checkRequired($required, $key, $value)){
					$errors['new password'] = 'This value is too short';
					$isError = true;
				}
			}
		}

		if(!$isError) {

			// если текущий пароль введен неправильно, выводим ошибку
			if (!password_verify($cur_password, $user['password'])) {
				$errors['password'] = 'Please enter your correct password';
			}

			// если новый пароль совпадает с текущим, выводим ошибку
			else if (password_verify($new_password, $user['password'])) {
				$errors['new password'] = 'You can not use your current password value';
			}
			
			// проверяем, правильно ли пользователь ввёл новый пароль во второй раз
			else if ($new_password_confirm != $new_password) {
				$errors['new password'] = 'Passwords are not matched';
			}
		}
		
		// вывод ошибок, если они есть
		if (!empty($errors)) showErrors($errors); 

		// хэширование нового пароля
		$password = password_hash($new_password, PASSWORD_DEFAULT);

		//изменение пароля пользователя и вывод сообщения об этом
		$data = 
		[ 
			'id' => $id,
			'password' => $password
		];
		
		$sql = 'UPDATE users SET password = :password WHERE id = :id';
		execute($pdo, $sql, $data);

		echo('success');
	}
}

