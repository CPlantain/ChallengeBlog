<?php
	
	// проверка отправки формы
	function isFormSend(){
		// если метод запроса - POST и массив $_POST не пустой, возвращаем true
		if(($_SERVER['REQUEST_METHOD'] === 'POST') && (count($_POST) !== 0)) {
			return true;
		} else {
			return false;
		}
	}

	// обработка пользовательского ввода из формы
  	function filter($variable){
		$variable = strip_tags($variable);
		$variable = htmlspecialchars($variable);
		$variable = trim($variable);
		return $variable;
	}

	// проверка обязательных полей формы
	function checkRequired($required, $key, $value){
		// если поле пустое, вернуть false
		if(!$value){
			return false;
		} 
		// если значение больше 0 и меньше или равно 3, вернуть false
		else if(strlen($value) > 0 && strlen($value) <= 3){
			return false;
		} 
		else {
			return true;
		}	
	}

	// проверка формата почты 
	function checkEmail($email){
		if(strlen($email) > 0 && !filter_var($email, FILTER_VALIDATE_EMAIL)){
			return false;
		} else {
			return true;
		}
	}

	// валидация изображения
	function validatePic($picture) {
		// проверка допустимых MIME типов файла
		if(($picture['type'] != 'image/gif') && ($picture['type'] != 'image/png') && ($picture['type'] != 'image/jpeg') && ($picture['type'] != 'image/jpg')){
			return false;
		} 
		// проверка допустимого размера файла
		else if($picture['size'] > 5 * 1024 * 1024){
			return false;
		}
		else {
			return true;
		}
	}

	// загрузка изображения на сервер
	function uploadPic($picture, $path) {
		// генерация уникального имени файла
		$name = md5(microtime()) . '.' . substr($picture['type'], strlen('image/'));
		$upload_file = $path . $name;

		// перемещение файла из временного хранилища в папку на сервере
		move_uploaded_file($picture['tmp_name'], $upload_file);

		// если файл не был загружен или его имя было равно пустой строке, присваиваем имени null 
		if(!$picture || ($picture['name'] == '')) {
			$name = null;
		}

		return $name;
	}

	// удаление изображения с сервера
	function deletePic($img, $path){

		// получаем путь к файлу из пути к папке и имени файла
		$file_path = $path . $img;

		// если такой файл существует, удаляем его
		if (file_exists($file_path)) {
			unlink($file_path);
		}
	}

	// показ ошибок валидации формы
	function showErrors($errors){
		// выводим первую ошибку из массива и закрываем скрипт
		foreach ($errors as $key => $value) {
			echo($key . ': ' . $value);
			break; 
		}
		die;
	}