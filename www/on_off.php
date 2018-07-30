<?php
include_once "config2.php"; //Подключаемся к бае-данных
header("content-type: text/html; charset=utf-8"); //Устанавливаем кодировку
session_start(); //Запускаем сессию

if(isset($_SESSION['id'])){header("location: /index.php");} // Если авторизирован - выполняем переход

// Регистрация
if(isset($_POST['reg_ok'])){ //  Если нажато - Зарегистрироваться
    if($_POST['password'] != $_POST['password_2']){ // Если пароли не совпадают
        $_SESSION['msg'] = "Пароли не совпали!";} // Выдаём ошибку
    else{ // Иначе идём дальше
        if($_POST['name'] == '' ||
            $_POST['mail'] == '' ||
            $_POST['login'] == '' ||
            $_POST['password'] == ''){ // Проверяем на заполнение [ * ] необходимых полей
            $_SESSION['msg'] = "Поля отмеченные * обязательны для заполнения!";} // => Если не заполнены - выдаём ошибку
        else{ // Иначе идём дальше
            $mail = $_POST['mail']; // Значение формы [ mail ]
            $login = $_POST['login']; // Значение формы [ login ]
            $sql_res = mysql_query("SELECT id FROM reg WHERE login='$login' OR mail='$mail'") or die(mysql_error());
            if(mysql_num_rows($sql_res) != 0 ){ // Если пользователь с такими данными существует
                $_SESSION['msg'] = "Пользователь с таким логином и/или почтой уже существует!";} // Выдаем ошибку
            else{ // Если нет - идём дальше
                $name = $_POST['name']; // Значение формы [ name ]
                $mail = $_POST['mail']; // Значение формы [ mail ]
                $login = $_POST['login']; // Значение формы [ login ]
                $password = $_POST['password']; // Значение формы [ password ]
                $ip = $_SERVER['REMOTE_ADDR']; // ip адрес пользователя

                $sql_r = "INSERT INTO `reg`(`name`, `mail`,`login`, `password`, `ip`)
    VALUES ('{$name}','{$mail}','{$login}','{$password}','{$ip}')";
include_once "config.php";
                $link = mysqli_connect($hostname, $user, $pass, $db) or die("Не могу подключиться к базе данных! Причина:".mysqli_error($link));
                mysqli_query($link, "set names 'utf8'");
                if(mysqli_query($link, $sql_r)){
                }
                else {
                    echo 'Ошибка<br/>'; die();
                }

                // Определяем новый id
                $id = mysql_insert_id();
                $sql_res = mysql_query("SELECT * FROM reg WHERE id=$id");
                $arr = mysql_fetch_assoc($sql_res);
                $_SESSION['id'] = $arr['id']; // Сохраняем его в сессию
                $id = $_SESSION['id'];
//                $usr = mysqli_fetch_assoc(mysqli_query("SELECT * FROM reg WHERE id=$id")); // =========> Забираем значение
                header("location: /index.php"); exit;}}}} // Bыполняем переход
// Авторизация
if(isset($_POST['avto_ok'])){ // Если нажато - Войти
    $login = $_POST['login']; // Значение формы [ login ]
    $password = $_POST['password']; // Значение формы [ password ]
    $sql_res = mysql_query("SELECT * FROM reg WHERE login='$login'") or die(mysql_error()); // Ищем в таблице
    if(mysql_num_rows($sql_res) != 0 ){ // Если запись есть
        $arr = mysql_fetch_assoc($sql_res);
        if($arr['password'] === $password){ // Если пароль введён верный
            $_SESSION['id'] = $arr['id']; // Записываем значение id в сессию
            $id = $_SESSION['id']; // Присваеваем в переменную $id
            header("location: /index.php"); exit;} // Выполняем переход
        else{ // Иначе
            $_SESSION['msg'] = "Неверный логин и/или пароль!";}} // Выводим сообщение
    $_SESSION['msg'] = "Пользователь не найден!";} // Выводим сообщение
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="css/style.css?r=<?= time(); ?>" >
    <title>Гостевая книга</title>
</head>
<body>
<div id="block">

    <!--Основной блок-->
    <section>
        <center>
    <?php
    if(isset($_SESSION['msg'])){ // Если есть ОШИБКА
        echo $_SESSION['msg']; // ВЫВОДИМ
        unset ($_SESSION['msg']);} ?>

<!--            кнопки-->
    <form action="" method="post">
        <button style="width:auto;
                     padding:5px 15px 5px 15px;
                     margin-left:50px;
                     -moz-appearance:none;
                     -webkit-appearance:none;
                     -ms-appearance:none;
                     appearance:none;
                     background-color:#FFF;
                     color:#666 !important;
                     cursor:pointer;
                     display:inline-block;
                     font-size:24px;
                     text-align:center;
                     text-decoration:none;
                     border:#999 1px solid;
                     -moz-border-radius:5px 5px 5px 5px;
                     -webkit-border-radius:5px 5px 5px 5px;
                     -khtml-border-radius:5px 5px 5px 5px;
                     border-radius:5px 5px 5px 5px;
                     behavior:url(border-radius.htc);" name="avt" type="submit">Авторизация</button>
        <button style="width:auto;
                     padding:5px 15px 5px 15px;
                     margin-left:50px;
                     -moz-appearance:none;
                     -webkit-appearance:none;
                     -ms-appearance:none;
                     appearance:none;
                     background-color:#FFF;
                     color:#666 !important;
                     cursor:pointer;
                     display:inline-block;
                     font-size:24px;
                     text-align:center;
                     text-decoration:none;
                     border:#999 1px solid;
                     -moz-border-radius:5px 5px 5px 5px;
                     -webkit-border-radius:5px 5px 5px 5px;
                     -khtml-border-radius:5px 5px 5px 5px;
                     border-radius:5px 5px 5px 5px;
                     behavior:url(border-radius.htc);" name="reg" type="submit">Регистрация</button>
    </form>

    <?php
    if(isset($_POST['avt'])){include "authoriz.php";} // Если нажата АВТОРИЗАЦИЯ
    if(isset($_POST['reg'])){include "register.php";}  // Если нажата РЕГИСТРАЦИЯ
     ?>
        </center>
    </section>

    <!--Присоединяем подвал-->
    <?php
    include("include/footer.php");
    ?>

</div>
</body>
</html>