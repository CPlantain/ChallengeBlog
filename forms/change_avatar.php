<?php 
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверяем, была ли отправлена форма
if(($_SERVER['REQUEST_METHOD'] === 'POST')){

	// сохранение данных в переменную
	$avatar = $_FILES['avatar'];
	
	// валидация и сбор ошибок в массив
	$errors = [];

	// проверка, загружен ли файл
	if($avatar == null){
		$errors['avatar'] = 'Please choose the file';
	} 

	// проверка соответствия файла к требованиям
	else if(!validatePic($avatar)){
		$errors['avatar'] = 'Wrong file loaded';
	} 	

	// если ошибки есть, выводим их
	if (!empty($errors)) showErrors($errors);

	// если у пользователя уже есть аватар, сначала удаляем его с сервера
	$data = [ 'id' => $_SESSION['user']['id'] ];
	$sql = 'SELECT avatar FROM users WHERE id = :id';
	$user = getRow($pdo, $sql, $data);

	if(checkImage($user['avatar'])) {
		
		$path = '../uploads/avatars/';
		deletePic($user['avatar'], $path);
	}

	// загружаем новое изображение
	$path = '../uploads/avatars/';
	$picName = uploadPic($avatar, $path);
	
	if($picName != null) {
		// изменение данных пользователя и вывод сообщения об этом
		$data =
		[
			'avatar' => $picName,
			'id' => $_SESSION['user']['id']
		];

		$sql = 'UPDATE users SET avatar = :avatar WHERE id = :id';
		execute($pdo, $sql, $data);

		echo('success');
	} 
	else {
		echo('File uploading failed');
	}
}