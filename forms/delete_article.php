<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// достаём данные об id пользователя и имени изображения из базы данных
$id = $_GET['id'];

$data = [ 'id' => $id ];
$sql = 'SELECT user_id, picture FROM articles WHERE id = :id';
$article = getRow($pdo, $sql, $data);

// проверяем права доступа пользователя к этой статье
if(!checkAuthor($article['user_id'])){
	// в случае отказа адресуем на главную
	header('Location: /');
	exit();
}

// если пользователь прошел проверку, сначала удаляем картинку статьи с сервера
else {
	if(checkImage($article['picture'])){

		$path = '../uploads/article_images/';
		deletePic($article['picture'], $path);
	}

	// затем удаляем саму статью из базы данных
	$data = [ 'id' => $id ];
	$sql = 'DELETE FROM articles WHERE id = :id';
	execute($pdo, $sql, $data);

	// возвращаем пользователя на страницу, с которой он перешёл, либо на главную
	$redirect = $_SERVER['HTTP_REFERER'];
	if($redirect == 'http://blog/article.php?id=' . $_GET['id']){
		$redirect = '/';
	}
	header('Location: ' . $redirect);
}
