<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверка прав доступа пользователя
require_once "adm_auth.php";

$title = 'All users';
// шапка 
require_once "includes/admin_header.php";
// боковое меню
require_once "includes/admin_sidebar.php";
?>

<!-- основная часть -->
<div class="main_adm col-md-10 px-5 pt-4 pb-5">
	<!-- выводим список всех пользователей -->
	<h3>Users:</h3>

	<!-- постраничный вывод всех пользователей из бд -->
	<?php 

	// определяем количество пользователей на странице
	$posts_per_page = 4;

	// считаем количество пользователей в бд
	$sql = 'SELECT COUNT(*) FROM users';
	$posts_cnt = getColumn($pdo, $sql);

	// получаем данные для постраничного вывода и выводим всех пользователей
	$pag_data = pagination($pdo, $posts_cnt, $posts_per_page);
	$cur_page_first_post = $pag_data['cur_page_first_post'];

	$sql = 'SELECT * FROM users LIMIT :limitVal, :offsetVal';
	$users = getAllLimit($pdo, $sql, $cur_page_first_post, $posts_per_page);
	?>
	<table class="table_main table table-hover mt-4">
		<tr>
			<th>id</th>
			<th>Login</th>
			<th>Email</th>
			<th>Is Admin</th>
			<th>Avatar</th>
		</tr>
						
		<?php						
			
		foreach ($users as $user): ?>
			<tr>
				<th><?= $user['id']; ?></th>
				<td><?= $user['login']; ?></td>
				<td><?= $user['email']; ?></td>
				<td><?= $user['isAdmin']; ?></td>
				
				<!-- если у пользователя нет аватара, выводим иконку по умолчанию -->
				<?php if(!checkImage($user['avatar'])){
					$user['avatar'] = 'default_icon.png';
				} ?>
				<td><img src="../uploads/avatars/<?= $user['avatar']; ?>" class="avatar_small rounded-circle"></td>					
			</tr>
		<? endforeach; ?>
	</table>

	<!-- пагинация -->
	<?php
	// отключение кнопки назад
	$p = $pag_data['p'];
	$disabled = disableItem($p, $pag_data['pages_cnt']);
	?>
	<ul class="pagination">	
		<li class="page-item <?= $disabled['item_prev']; ?>">
	        <a class="page-link" href="/admin/users.php?p=<?= ($p - 1) ?>" aria-label="Previous">
	            <span aria-hidden="true">&laquo;</span>
	            <span class="sr-only">Previous</span>
	        </a>
	    </li>
		
		<?php 

		// вывод ссылок на страницы, активация текущего элемента
		for ($p = 1; $p <= $pag_data['pages_cnt']; $p++):

			$active = activateItem($p); 

			?>
			<li class="page-item <?= $active; ?>"><a class="page-link" href="/admin/users.php?p=<?= $p ?>"><?= $p; ?></a></li>

		<? endfor; ?>
		
		<?php

		// отключение кнопки вперед
		$p = $_GET['p'];
		$disabled = disableItem($p, $pag_data['pages_cnt']);
		?>
		<li class="page-item <?= $disabled['item_next']; ?>">
	        <a class="page-link" href="/admin/users.php?p=<?= ($p + 1) ?>" aria-label="Next">
	        	<span aria-hidden="true">&raquo;</span>
	        	<span class="sr-only">Next</span>
	        </a>
	    </li>
	</ul>
	
</div>

<!-- подвал -->
<?php require_once "includes/admin_footer.php"; ?>

