<?php 
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверяем, отправлена ли форма
if(isFormSend()){
	// для удобства сохраняем массив $_POST в переменную
	$form = $_POST;

	// фильтрация и сохранение данных в переменные
	$user_id = $_SESSION['user']['id'];
	$article_id = $form['article_id'];
	$content = filter($form['content']);

	// валидация и сбор ошибок в массив
	$required = ['content'];
	$errors = [];
	$isError = false;

	foreach ($form as $key => $value) {
		if(in_array($key, $required)){
			// проверка на заполненность и длину значения полей
			if(!checkRequired($required, $key, $value)){
				$errors[$key] = 'This value is too short';
				$isError = true;
			}
		}
	}
	// если ошибки есть, выводим их
	if (!empty($errors)) showErrors($errors);

	// добавление комментария в базу данных и вывод его на страницу
	$data = 
	[
		'user_id' => $user_id, 
		'article_id' => $article_id, 
		'content' => $content
	];

	$sql = 'INSERT INTO comments(user_id, article_id, content) VALUES(:user_id, :article_id, :content)';
	execute($pdo, $sql, $data);

	echo('success');
}
