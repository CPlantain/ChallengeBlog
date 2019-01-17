<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "config.php";
require_once "functions/helpers.php";
require_once "functions/helpers_db.php";

// проверяем права доступа к профилю пользователя
if(!checkAuthor($_GET['user_id'])){
	// адресуем на главную в случае отказа
	header('Location: /');
	exit();
}

$title = 'My profile';

// шапка
require_once "includes/header.php";
?>

<div class="main col-md-8 px-5 pt-1">
	<!-- Страница профиля авторизованного пользователя -->
	<div class="pt-4 pb-3 px-3">

		<!-- форма редактирования данных пользователя -->
		<p class="profile_p">Profile</p>
		
		<?php
		// получаем данные пользователя и заносим их в форму
		$data = [ 'id' => $_SESSION['user']['id'] ];
		$sql = 'SELECT avatar, login, email FROM users WHERE id = :id';
		$user = getRow($pdo, $sql, $data);

		// если у пользователя нет аватарки, загружаем картинку по умолчанию
		if(!checkImage($user['avatar'])){
			$user['avatar'] = 'default_icon.png';
		}
		?>
		<img src="uploads/avatars/<?= $user['avatar']; ?>" class="profile_img rounded-circle my-2 float-right" alt="">

		<p class="profile_p">Your info:</p>

		<!-- уведомление об успешном обновлении данных пользователя -->
		<div class="message alert alert-success col-md-6" name="successAlert1"></div>			
		
		<form class="pb-3">
			<div class="form-group row">
			    <div class="col-md-11">
			      	<input type="text" class="form-control" name="login" placeholder="Login" value="<?= $user['login']; ?>">
			    </div>
		  	</div>

		  	<div class="form-group row">
			    <div class="col-md-11">
			      	<input type="email" class="form-control" name="email" placeholder="Email" value="<?= $user['email']; ?>">
			    </div>
		  	</div>

		  	<div class="form-group row">
			    <div class="col-md-11">
			      	<input type="password" class="form-control" name="password" placeholder="Enter your password">
			    </div>
		  	</div>

		  	<input type="hidden" name="user_id" value="<?= $_GET['user_id']; ?>">

		  	<!-- уведомление об ошибке при заполнении формы -->
		  	<div class="message alert alert-danger" name="errorAlert1"></div>

		  	<div class="form-group row">
			    <div class="col-md-10">
			      	<button type="button" class="btn btn-primary" name="update_user">Update</button>
			    </div>
		  	</div>
		</form>

		<form>
			<!-- изменение аватара пользователя -->

			<p class="form_p">Change Avatar:</p>

			<!-- уведомление об успешном обновлении данных пользователя -->
			<div class="message alert alert-success" name="successAlert2"></div>

			<div class="form-group row">							   
			    <div class="col-md-11">
			      	<input type="file" name="avatar_pic">
			    </div>
		  	</div>

		  	<!-- уведомление об ошибке при заполнении формы -->
		  	<div class="message alert alert-danger" name="errorAlert2"></div>

		  	<div class="form-group row">
			    <div class="col-md-10">
			      	<button type="button" class="btn btn-primary mb-3" name="change_avatar">Change avatar</button>
			    </div>
		  	</div>
		</form>
		
		<form>

			<!-- изменение пароля пользователя -->

			<p class="form_p">Change Password:</p>

			<!-- уведомление об успешной смене пароля -->
			<div class="message alert alert-success" name="successAlert3"></div>	

			<div class="form-group row">
			    <div class="col-md-11">
			      	<input type="password" class="form-control" name="cur_password" placeholder="Current password">
			    </div>
		  	</div>

		  	<div class="form-group row">
			    <div class="col-md-11">
			      	<input type="password" class="form-control" name="new_password" placeholder="New password">
			    </div>
		  	</div>

		  	<div class="form-group row">							    
			    <div class="col-md-11">
			      	<input type="password" class="form-control" name="new_password_confirm" placeholder="Confirm new password">
			    </div>
		  	</div>

		  	<input type="hidden" name="user_id" value="<?= $_GET['user_id']; ?>">

		  	<!-- уведомление об ошибке при заполнении формы -->
		  	<div class="message alert alert-danger" name="errorAlert3"></div>
		 
		  	<div class="form-group row">
			    <div class="col-md-10">
			      	<button type="button" class="btn btn-primary mb-3" name="change_pass">Change Password</button>
			    </div>
		  	</div>
		</form>
		<hr>
	</div>
	
	<!-- форма добавления новой статьи в блог -->
	<div class="pb-3 px-3">
		<p class="form_p">Add new article</p>

		<!-- уведомление об успешном добавлении статьи -->
		<div class="message alert alert-success" name="successAlert4"></div>

		<form name="new_article">
			<div class="form-group row">
			    <div class="col-md-11">
			      	<input type="text" class="form-control" name="title" placeholder="Title">
			    </div>
		  	</div>

		  	<div class="form-group row">
			    <div class="col-md-11">
			      	<select class="form-control custom-select" name="category">
					  	<option value="-1">Choose category...</option>
					  	<?php
					  	$sql = 'SELECT * FROM categories';
					  	$categories = getAllRows($pdo, $sql);

					  	foreach ($categories as $category): ?>
					  		<option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
					  	<? endforeach; ?>
					</select>
			    </div>
		  	</div>

		  	<div class="form-group row">
			    <div class="col-md-11">
			      	<textarea class="form-control" name="description" cols="100" rows="2" placeholder="Article description..."></textarea>
			    </div>
		  	</div>

		  	<div class="form-group row">
			    <div class="col-md-11">
			      	<textarea class="form-control" name="content" cols="100" rows="6" placeholder="Article text..."></textarea>
			    </div>
		  	</div>

		  	<div class="form-group row">							    
			    <label for="password" class="col col-form-label">Article picture:</label>
			    <div class="col-md-11">
			      	<input type="file" name="art_pic">
			    </div>
		  	</div>		

		  	<!-- уведомление об ошибке при заполнении формы -->
		  	<div class="message alert alert-danger" name="errorAlert4"></div>

		   	<div class="form-group row">
			    <div class="col-md-10">
			      	<button type="button" class="btn btn-primary" name="add_art">Add</button>
			    </div>
		  	</div>
		</form>
	</div>
</div>

<!-- сайдбар -->			
<?php require_once "includes/sidebar.php"; ?>

<!-- футер -->
<?php require_once "includes/footer.php"; ?>
