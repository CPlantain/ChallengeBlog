<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверка прав доступа пользователя
require_once "adm_auth.php";

// открываем буферизацию
ob_start();

$title = 'All comments';
// шапка 
require_once "includes/admin_header.php";
// боковое меню
require_once "includes/admin_sidebar.php";
?>

<!-- основная часть -->
<div class="main_adm col-md-10 px-5 pt-4 pb-5">

	<!-- выводим все комментарии -->
	<h3>Comments:</h3>
	<table class="table_main table table-hover mt-4">
		<tr>
			<th>id</th>
			<th>Author</th>
			<th>Text</th>
			<th>Post</th>
			<th>Publication date</th>
			<th></th>
		</tr>
						
		<?php
		$sql = 'SELECT c.id, login, c.content, article_id, title, c.pub_date FROM comments c JOIN articles a ON c.article_id = a.id JOIN users u ON c.user_id = u.id ORDER BY c.pub_date DESC';			
		$comments = getAllRows($pdo, $sql);					
			
		foreach ($comments as $comment): ?>
			<tr>
				<th><?= $comment['id']; ?></th>
				<td><?= $comment['login']; ?></td>
				<td><?= $comment['content']; ?></td>
				<td><?= $comment['title']; ?></td>
				<td><?= date("d.m.y", strtotime($comment['pub_date'])); ?></td>
				<td>
					<a href="?delete_comment=<?= $comment['id']; ?>" class="delete_btn btn btn-outline-light btn-sm ml-2 float-right">&#9747;</a>
				</td>					
			</tr>
		<? endforeach; ?>
	</table>

	<?php
	// проверка роли пользователя, удаление комментария
	if((!empty($_GET['delete_comment'])) && checkAdmin()){
		if(is_numeric($_GET['delete_comment'])){

			$data = [ 'id' =>  $_GET['delete_comment'] ];
			$sql = 'DELETE FROM comments WHERE id = :id';
			execute($pdo, $sql, $data);

			// после удаления перенаправляем пользователя на страницу, с которой он перешёл и возвращаем содержимое буфера

			$redirect = $_SERVER['HTTP_REFERER'];
			header('Location: ' . $redirect);
			ob_get_flush();
		}
	}
	?>	
	
</div>

<!-- подвал -->
<?php require_once "includes/admin_footer.php"; ?>

