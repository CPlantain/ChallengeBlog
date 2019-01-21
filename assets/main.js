$(document).ready(main);

// главная функция, содержащая все остальные функции ajax и jQuery
function main() {

    addComment();

    deleteItem();

    controlProfile();

    auth();

    register();

    updateArticle();

}

// вспомогательная функция, которая проверяет наличие jQuery элемента на странице
function existElem(jQueryElem) {
    return ($(jQueryElem).length !== 0);
}

// добавление комментария
function addComment() {

    // вызываем функцию только если на странице есть соответствующая кнопка
    var btnSend = $('button[name = send]');

    if (!existElem(btnSend)) {
    return
    }

    // при клике по кнопке получаем значения полей формы добавления комментария и сохраняем в переменные
    btnSend.on('click', function () {
        var content = $('textarea[name = comment]').val();
        var article_id = $('input[name = id]').val();

        // создание и обработка ajax запроса
        $.ajax({
            method: "POST",
            url: "./forms/add_comment.php",
            cache: false,
            data: { content: content, article_id: article_id },

            // вывод уведомлений в зависимости от результата отправки формы
            success: function(data) {
                if(data == 'success') {                     
                    
                    // в случае успеха скрываем сообщение об ошибке и очищаем поле комментария, перезагружаем страницу чтобы новый комментарий вывелся на страницу из базы данных
                    $('div[name = errorAlert]').hide();
                    $('textarea[name = comment]').val('');

                    location.reload();
                } else {                    

                    // показываем сообщение об ошибке   
                    $('div[name = errorAlert]').show();
                    $('div[name = errorAlert]').text(data);
                }
            }
        });
    });
}


// удаление элемента
function deleteItem() {

    // страница статьи, блога, страницы статей, комментариев и категорий в админ панели

    // вызываем функцию только если на странице есть соответствующая кнопка
    var btnDel = $('a.delete_btn');

    if (!existElem(btnDel)) {
        return
    }
    // при клике по кнопке просим пользователя подтвердить удаление
    btnDel.on('click', function () {
        var is_confirm = confirm("Are you sure?");
        if(is_confirm == true){

            // если пользователь согласен, адресуем его на сценарий с удалением
            document.location.href = $("a.delete_btn").attr("href");
            $('div[name = successAlert]').show();
        } else {            

            // если пользователь отменил действие оставляем его на странице 
            $("a.delete_btn").attr("href", "");
        }
    });
}


// редактирование данных пользователя, смена пароля, смена аватара, добавление новой статьи
function controlProfile() {

    // страница профиля

    // обработка запроса из формы редактирования данных пользователя
    // вызываем функцию только если на странице есть соответствующая кнопка
    var btnChangeAvatar = $('button[name = change_avatar]');

    if (!existElem(btnChangeAvatar)) {
        return
    }

    // при клике по кнопке получаем значения полей формы и сохраняем в переменные
    $('button[name = update_user]').on('click', function () {
        var user_id = $('input[name = user_id]').val();
        var login = $('input[name = login]').val();
        var email = $('input[name = email]').val();
        var password = $('input[name = password]').val();

        // создание и обработка ajax запроса
        $.ajax({
            method: "POST",
            url: "./forms/update_user_data.php",
            cache: false,
            data: { user_id: user_id, login: login, email: email, password: password },

            // вывод уведомлений в зависимости от результата отправки формы
            success: function (data) {
                if (data == 'success') {
                    $('div[name = successAlert1]').show();
                    $('div[name = successAlert1]').text('Your user info was updated');
                    $('div[name = errorAlert1]').hide();

                    $('input[name = password]').val('');
                } else {
                    $('div[name = errorAlert1]').show();
                    $('div[name = errorAlert1]').text(data);
                    $('div[name = successAlert1]').hide();
                }
            }
        });
    });

    // обработка запроса из формы редактирования аватара пользователя
    // при клике по кнопке получаем значения полей формы и сохраняем в переменные
    btnChangeAvatar.on('click', function () {
        var avatar = $('input[name = avatar_pic]').prop('files')[0];
        var form_data = new FormData();
        form_data.append('avatar', avatar);

        // создание и обработка ajax запроса
        $.ajax({
            method: "POST",
            url: "./forms/change_avatar.php",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,

            // вывод уведомлений в зависимости от результата отправки формы
            success: function (data) {
                if (data == 'success') {
                    $('div[name = successAlert2]').show();
                    $('div[name = successAlert2]').text('Your avatar was changed');
                    $('div[name = errorAlert2]').hide();

                     location.reload();
                } else {
                    $('div[name = errorAlert2]').show();
                    $('div[name = errorAlert2]').text(data);
                    $('div[name = successAlert2]').hide();
                }
            }
        });
    });

    // обработка запроса из формы смены пароля

    // при клике по кнопке получаем значения полей формы и сохраняем в переменные
    $('button[name = change_pass]').on('click', function () {
        var user_id = $('input[name = user_id]').val();
        var cur_password = $('input[name = cur_password]').val();
        var new_password = $('input[name = new_password]').val();
        var new_password_confirm = $('input[name = new_password_confirm]').val();

        // создание и обработка ajax запроса
        $.ajax({
            method: "POST",
            url: "./forms/password_change.php",
            cache: false,
            data: { user_id: user_id, cur_password: cur_password, new_password: new_password, new_password_confirm: new_password_confirm },

            // вывод уведомлений в зависимости от результата отправки формы
            success: function (data) {
                if (data == 'success') {
                    $('div[name = successAlert3]').show();
                    $('div[name = successAlert3]').text('Your password was changed');
                    $('div[name = errorAlert3]').hide();

                    // очищаем поля
                    $('input[name = cur_password]').val('');
                    $('input[name = new_password]').val('');
                    $('input[name = new_password_confirm]').val('');
                } else {
                    $('div[name = errorAlert3]').show();
                    $('div[name = errorAlert3]').text(data);
                    $('div[name = successAlert3]').hide();
                }
            }
        });
    });

    // обработка запроса из формы добавления новой статьи
    // при клике по кнопке получаем значения полей формы и сохраняем в переменные
    $('button[name = add_art]').on('click', function () {
        var art_pic = $('input[name = art_pic]').prop('files')[0];
        var form_data = new FormData();
        form_data.append('art_pic', art_pic);

        var article_data = $('form[name = new_article]').serializeArray();
        $.each(article_data, function (key, input) {
          form_data.append(input.name, input.value);
        });

        // создание и обработка ajax запроса
        $.ajax({
            method: "POST",
            url: "./forms/create_article.php",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,

            // вывод уведомлений в зависимости от результата отправки формы
            success: function (data) {
                if (data == 'success') {
                    $('div[name = successAlert4]').show();
                    $('div[name = successAlert4]').text('You added new article!');
                    $('div[name = errorAlert4]').hide();

                    // очищаем поля после успешного добавления
                    $('input[name = title]').val('');
                    $('select[name = category]').val('0');
                    $('textarea[name = description]').val('');
                    $('textarea[name = content]').val('');
                    $('input[name = art_pic]').val('');
                } else {
                    $('div[name = errorAlert4]').show();
                    $('div[name = errorAlert4]').text(data);
                    $('div[name = successAlert4]').hide();
                }
            }
        });
    });
}


// авторизация
function auth() {

    // страница авторизации
    // вызываем функцию только если на странице есть соответствующая кнопка
    var btnSignIn = $('button[name = sign_in]');

    if (!existElem(btnSignIn)) {
        return
    }

    // при клике по кнопке получаем значения полей формы и сохраняем в переменные
    btnSignIn.on('click', function () {
        var login = $('input[name = login]').val();
        var password = $('input[name = password]').val();

        // создание и обработка ajax запроса
        $.ajax({
            method: "POST",
            url: "./forms/login.php",
            cache: false,
            data: { login: login, password: password },

            // вывод уведомлений в зависимости от результата отправки формы
            success: function (data) {
                if (data == 'success') {
                    $('div[name = successAlert]').show();
                    $('div[name = successAlert]').text('You have logged in!');
                    $('div[name = errorAlert]').hide();

                    // после успешной авторизации переадресуем пользователя на главную страницу
                    document.location = './index.php';
                } else {
                    $('div[name = errorAlert]').show();
                    $('div[name = errorAlert]').text(data);
                    $('div[name = successAlert]').hide();
                }
            }
        });
    });
}


// регистрация
function register() {

    // страница регистрации

    // вызываем функцию только если на странице есть соответствующая кнопка
    var btnSingUp = $('button[name = sign_up]');

    if (!existElem(btnSingUp)) {
        return
    }

    // при клике по кнопке получаем значения полей формы и сохраняем в переменные
    btnSingUp.on('click', function () {
        var login = $('input[name = login]').val();
        var email = $('input[name = email]').val();
        var password = $('input[name = password]').val();
        var password_confirm = $('input[name = password_confirm]').val();

        // создание и обработка ajax запроса
        $.ajax({
            method: "POST",
            url: "./forms/signup.php",
            cache: false,
            data: { login: login, email: email, password: password, password_confirm: password_confirm },

            // вывод уведомлений в зависимости от результата отправки формы
            success: function (data) {
                if (data == 'success') {
                    $('div[name = successAlert]').show();
                    $('div[name = successAlert]').text('You have been registered successfully!');
                    $('div[name = errorAlert]').hide();

                    // очищаем все поля после успешной регистрации
                    $('input[name = login]').val('');
                    $('input[name = email]').val('');
                    $('input[name = password]').val('');
                    $('input[name = password_confirm]').val('');
                } else {
                    $('div[name = errorAlert]').show();
                    $('div[name = errorAlert]').text(data);
                    $('div[name = successAlert]').hide();
                }
            }
        });
    });
}


// редактирование статьи
function updateArticle() {

    // страница редактирования статьи

    // вызываем функцию только если на странице есть соответствующая кнопка
    var btnUpdateArt = $('button[name = update_article]');

    if (!existElem(btnUpdateArt)) {
        return
    }

    // при клике по кнопке получаем значения полей формы и сохраняем в переменные
    btnUpdateArt.on('click', function () {
        var art_pic = $('input[name = art_pic]').prop('files')[0];
        var form_data = new FormData();
        form_data.append('art_pic', art_pic);

        var article_data = $('form[name = edit_article]').serializeArray();
        $.each(article_data, function (key, input) {
          form_data.append(input.name, input.value);
        });

        // создание и обработка ajax запроса
        $.ajax({
            method: "POST",
            url: "./forms/update_article.php",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,

            // вывод уведомлений в зависимости от результата отправки формы
            success: function (data) {
                if (data == 'success') {

                    // перезагружаем страницу для отображения изменённой картинки
                    location.reload();

                    $('div[name = successAlert]').show();
                    $('div[name = successAlert]').text('Your article have been edited');
                    $('div[name = errorAlert]').hide();
                } else {
                    $('div[name = errorAlert]').show();
                    $('div[name = errorAlert]').text(data);
                    $('div[name = successAlert]').hide();
                }
            }
        });
    });
}




