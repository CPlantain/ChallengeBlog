<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверка прав доступа пользователя
require_once "./adm_auth.php";

// открываем буферизацию
ob_start();

$title = 'All categories';
// шапка 
require_once "./includes/admin_header.php";
// боковое меню
require_once "./includes/admin_sidebar.php";
?>

<!-- основная часть -->
<div class="main_adm col-md-10 px-5 pt-4 pb-5">

	<!-- вывод всех категорий -->
	<h3>Categories:</h3>
	<table class="table_main table table-hover mt-4">
		<tr>
			<th>id</th>
			<th>Name</th>
			<th>Posts count</th>
			<th></th>
		</tr>
		
		<!-- форма добавления новой категории -->
		<form method="POST" action="./categories.php">
			<tr>
				<td></td>
				<td>
					<input type="text" class="form-control" placeholder="Category name" name="category_name"></td>
				<td></td>
				<td>
					<button type="submit" class="btn btn-primary float-right" name="create_category">Create new</button>
				</td>
			</tr>
		</form>
		
		<?php
		// берем данные о категории для вывода
		$sql = 'SELECT c.id AS cat_id, name, COUNT(a.id) AS count FROM categories c LEFT JOIN articles a ON c.id = a.category_id GROUP BY cat_id, name';
		$categories = getAllRows($pdo, $sql);									
			
		foreach ($categories as $category): 

			// если нажата кнопка "редактировать", отображаем форму с уже имеющимся названием категории из базы данных
			if((!empty($_GET['edit_category'])) && ($_GET['edit_category'] == $category['cat_id']) &&checkAdmin()): ?>

			<form method="POST" action="./categories.php?edit_category=<?= $_GET['edit_category']; ?>">
				<tr>
					<th><?= $category['cat_id'] ?></th>
					<td>
						<input type="text" class="form-control" name="update_category_name" value="<?= $category['name'] ?>">
					</td>
					<td></td>
					<td>
						<!-- также меняем кнопки удаления и редактирования на кнопку сохранения изменений -->
						<button type="submit" name="update_category" class="edit_btn btn btn-light btn-sm float-right">Save changes</button>
					</td>					
				</tr>
			</form>

			<? else: ?>

				<!-- если GET параметры пусты, отображаем сами категории и кнопки удаления категории и редактирования -->
				<tr>
					<th><?= $category['cat_id']; ?></th>
					<td><?= $category['name']; ?></td>
					<td><?= $category['count']; ?></td>
					<td>
						<a href="?delete_category=<?= $category['cat_id']; ?>" class="delete_btn btn btn-outline-light btn-sm ml-2 float-right">&#9747;</a>

						<a href="?edit_category=<?= $category['cat_id']; ?>" class="edit_btn btn btn-light btn-sm float-right">Edit</a>
					</td>					
				</tr>
			<? endif; ?>
			
		<? endforeach; ?>				
	</table>

	<?php 

	// обработчик формы создания новой категории
		// проверяем, отправлена ли форма и отправлена ли именно она
		if(isFormSend() && (isset($_POST['create_category'])) && checkAdmin()){

			// если да, сохраняем данные POST в переменную
			$form = $_POST;
			// фильтруем данные и сохраняем в переменную
			$name = filter($form['category_name']);

			// обязательные поля и массив с ошибками
			$required = ['category_name'];
			$errors = [];
			$isError = false;

			// валидация формы
			foreach ($form as $key => $value) {
				if(in_array($key, $required)){

					// проверка заполненности полей
					if(!checkRequired($required, $key, $value)){
						$errors['category name'] = 'This value is too short';
						$isError = true;
					}	
				}
			}
			if(!$isError) {
				// если ошибок нет, проверяем, нет ли в бд категории с таким названием
				$data = [ 'name' => $name ];
				$sql = 'SELECT * FROM categories WHERE name = :name';
				$category = getRow($pdo, $sql, $data);
				
				if($category){
					$errors['category name'] = 'This category already exists';
				}
			}
					
			// если ошибки есть, выводим 
			if(!empty($errors)) showErrors($errors);

			// если ошибок не было, добавляем новую категорию в базу данных
			$data = [ 'name' => $name ];
			$sql = 'INSERT INTO categories(name) VALUES(:name)';
			execute($pdo, $sql, $data);

			// перенаправляем пользователя на страницу категорий и возвращаем содержимое буфера
			$redirect = $_SERVER['HTTP_REFERER'];
			header('Location: ' . $redirect);
			ob_get_flush();
		} 

	// обработчик формы редактирования категории
		// проверяем, отправлена ли конкретная форма
		if(isFormSend() && (isset($_POST['update_category'])) && checkAdmin()) {

			// если да, сохраняем данные POST в переменную
			$form = $_POST;
			// фильтруем данные и сохраняем в переменную
			$new_name = filter($form['update_category_name']);

			// обязательные поля и массив с ошибками
			$required = ['update_category_name'];
			$errors = [];
			$isError = false;

			// валидация формы
			foreach ($form as $key => $value) {
				if(in_array($key, $required)){
					// проверка заполенности полей
					if(!checkRequired($required, $key, $value)){
						$errors['new category name'] = 'This value is too short';
						$isError = true;
					}	
				}
			}
			if(!$isError) {
				// если ошибок нет, проверяем, нет ли в бд категории с таким названием
				$data = [ 'name' => $new_name ];
				$sql = 'SELECT * FROM categories WHERE name = :name';
				$category = getRow($pdo, $sql, $data);
				
				if($category){
					$errors['new category name'] = 'This category already exists';
				}
			}

			// если ошибки есть, выводим 
			if(!empty($errors)) showErrors($errors);

			// если ошибок не было, изменяем название выбранной категории в базе данных
			$data = [ 'name' => $new_name, 'id' => $_GET['edit_category'] ];
			$sql = 'UPDATE categories SET name = :name WHERE id = :id';
			execute($pdo, $sql, $data);

			// перенаправляем пользователя на страницу категорий, чтобы избежать повторной отправки формы и возвращаем содержимое буфера
			header('Location: ./categories.php');
			ob_get_flush();
		}

	// уделение категории
		// проверяем, нажата ли кнопка удаления 
		if((!empty($_GET['delete_category'])) && checkAdmin()){
			if(is_numeric($_GET['delete_category'])){

				// если нажата, у всех постов из этой категории меняем категорию на unnamed с id = 0
				$data = 
				[ 
					'def_category' => 0, 
					'category_id' => $_GET['delete_category'] 
				];

				$sql = 'UPDATE articles SET category_id = :def_category WHERE category_id = :category_id';
				execute($pdo, $sql, $data);

				// после удаляем категорию
				$data = [ 'id' => $_GET['delete_category'] ];
				$sql = 'DELETE FROM categories WHERE id = :id';
				execute($pdo, $sql, $data);

				// перенаправляем пользователя на страницу категорий, чтобы избежать повторной отправки формы и возвращаем содержимое буфера
				$redirect = $_SERVER['HTTP_REFERER'];
				header('Location: ' . $redirect);
				ob_get_flush();	
			}					
		}
	?>
	
</div>

<!-- подвал -->
<?php require_once "./includes/admin_footer.php"; ?>