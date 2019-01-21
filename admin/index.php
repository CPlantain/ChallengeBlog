<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверка прав доступа пользователя
require_once "./adm_auth.php";

$title = 'Common statistics';
// шапка 
require_once "./includes/admin_header.php";
// боковое меню
require_once "./includes/admin_sidebar.php";
?>

<!-- основная часть -->
<div class="main_adm col-md-10 px-5 pt-4 pb-5">
	<?php
	// берем из базы данных и выводим логин текущего администратора
	$data = [ 'id' => $_SESSION['user']['id'], 'isAdmin' => 1 ];
	$sql = 'SELECT login FROM users WHERE id = :id AND isAdmin = :isAdmin';
	$user = getRow($pdo, $sql, $data);
	?>
	<p class="adm_p display-4 mb-4">Current admin: <?= $user['login']; ?></p>
	
	<!-- вывод общей статистики сайта -->
	<h3>Common statistics:</h3>
	<table class="form_p table table-hover">
		<tr>
			<!-- считаем количество категорий -->
			<td>Categories count:</td>
	
			<?php
			$sql = 'SELECT COUNT(*) AS count FROM categories';					
			$categories = getRow($pdo, $sql);
			?>
			<td>(<?= $categories['count']; ?>)</td>
		</tr>
		<tr>
			<!-- количество статей -->
			<td>Articles count:</td>
	
			<?php
			$sql = 'SELECT COUNT(*) AS count FROM articles';					
			$articles = getRow($pdo, $sql);
			?>
			<td>(<?= $articles['count']; ?>)</td>
		</tr>
		<tr>
			<!-- количество скрытых статей -->
			<td>Hidden articles count:</td>
	
			<?php
			$data = [ 'hidden' => 1 ];
			$sql = 'SELECT COUNT(*) AS count FROM articles WHERE hidden = :hidden';
			$hid_articles = getRow($pdo, $sql, $data);

			?>
			<td>(<?= $hid_articles['count']; ?>)</td>
		</tr>
		<tr>
			<!-- количество комментариев -->
			<td>Comments count:</td>
	
			<?php
			$sql = 'SELECT COUNT(*) AS count FROM comments';					
			$comments = getRow($pdo, $sql);
			?>
			<td>(<?= $comments['count']; ?>)</td>
		</tr>
		<tr>
			<!-- количество пользователей -->
			<td>Users count:</td>
	
			<?php
			$sql = 'SELECT COUNT(*) AS count FROM users';					
			$users = getRow($pdo, $sql);
			?>
			<td>(<?= $users['count']; ?>)</td>
		</tr>				
	</table>
	
</div>

<!-- подвал -->
<?php require_once "./includes/admin_footer.php"; ?>

