<?php

require_once('config.php');
require_once('functions.php');
$link = mysqli_connect($hostname, $user, $pass, $db) or die("Не могу подключиться к базе данных! Причина:".mysqli_error($link));

header("content-type: text/html; charset=utf-8"); // Устанавливаем кодировку
session_start(); // Запускаем сессию
//if(!isset($_SESSION['id']) ){header("location: /on_off.php"); exit;} //  Если НЕ Авторизирован - возвращаем назад
$id = $_SESSION['id']; // id Пользователя
$reult = mysqli_query($link,"SELECT * FROM reg WHERE id='$id'");
$usr = mysqli_fetch_assoc($reult);
//  Выбираем данные
if(isset($_GET['stop'])) {
    session_destroy();
    unset($_GET['stop']);
//    header("location: /on_off.php");
//    exit;
}
$namesession = "Имя";

?>

<!--Главная страница-->
<!DOCTYPE html>
<html>
<head>
    <!--Присоединяем стили-->
    <link rel="stylesheet" type="text/css" href="css/style.css?r=<?= time(); ?>" >
    <title>Гостевая книга</title>
</head>
<body>
<div id="block">
    <!--Присоединяем шапку-->
    <?php
    include("include/header.php");
    ?>

    <!--Основной блок-->
    <section>

<!--        Запрос на удаление/изменение комментария-->
        <?php

        if(!empty($_GET['del'])){
            $idcom = $_GET['del'];
            delcomment($idcom);
        }

        if(!empty($_GET['update'])){
            updcomm($_GET['id'],$_GET['name'],$_GET['textcom']);
        }

        if(!empty($_GET['upd'])){

            $idcom = $_GET['upd'];

            updatecomment($idcom);

        } else {

        ?>

        <div id="blockmess">
            <form action="index.php" method="post">

                <input type="text" name="name" placeholder="<?php echo $namesession; ?>" style=" width: 15%; margin-left: 5%;" >
                <input type="checkbox" name="anon" style="margin-left: 1em;" > Анонимно
                <br>

                <input type="text" name="textcom" placeholder="Введите текст..." style=" width: 90%; height: 5em; margin-left: 5%;" required> <br>


                <br>
                <input  type="submit" value="Отправить" style="margin-left: 85%">
            </form>
            <br>
        </div>

        <?php } ?>

        <div id="blockcome">

<div id="coment">

    <?php

    if(!empty($_POST['textcom'])) {
        if(!empty($_POST['anon'])) {
            insertcomments($_POST['textcom']);
        }elseif (!empty($_SESSION['id'])){
            insertcomments($_POST['textcom'], $namesession);
        }
            elseif (!empty($_POST['name'])) {
                insertcomments($_POST['textcom'], $_POST['name']);
            } else {
            insertcomments($_POST['textcom']);
        }

    }


    //Вычисляем страницы
$num;
    if(!empty($_GET['num'])){
        $num = $_GET['num'];
    } else  $num = 6;

    $page = (int)$_GET['page'];

    $count = mysqli_query($link, "SELECT * FROM `comments` WHERE 1 ORDER BY id DESC ");
    $temp = mysqli_fetch_array($count);

    If ($temp[0] > 0) {
        $tempcount = $temp[0];

        // Находим общее число страниц
        $total = (($tempcount - 1) / $num) + 1;
        $total = intval($total);

        $page = intval($page);

        if (empty($page) or $page < 0) $page = 1;

        if ($page > $total) $page = $total;

        // Вычисляем начиная с какого номера
        // следует выводить товары
        $start = $page * $num - $num;

        $qury_start_num = " LIMIT $start, $num";


        printcomments($qury_start_num);

    }
    ?>

</div>

            <center>
<!--                <a href="index.php" > < </a><a href="index.php?page=1" > 1 </a><a href="index.php?page=2" > 2 </a><a href="index.php?page=3" > 3 </a><a href="index.php?page=4" > 4 </a><a href="index.php?page=5" > 5</a><a href="index.php" > > </a>-->
<ul>

    <?php

    //выводим страницы
                if ($page != 1){ $pstr_prev = '<li><a class="pstr-prev" href="index.php?page='.($page - 1).'">&lt;</a></li>';}
                if ($page != $total) $pstr_next = '<li><a class="pstr-next" href="index.php?page='.($page + 1).'">&gt;</a></li>';


                // Формируем ссылки со страницами
                if($page - 5 > 0) $page5left = '<li><a href="index.php?page='.($page - 5).'&num='.(int)$_GET['num'].'">'.($page - 5).'</a></li>';
                if($page - 4 > 0) $page4left = '<li><a href="index.php?page='.($page - 4).'&num='.(int)$_GET['num'].'">'.($page - 4).'</a></li>';
                if($page - 3 > 0) $page3left = '<li><a href="index.php?page='.($page - 3).'&num='.(int)$_GET['num'].'">'.($page - 3).'</a></li>';
                if($page - 2 > 0) $page2left = '<li><a href="index.php?page='.($page - 2).'&num='.(int)$_GET['num'].'">'.($page - 2).'</a></li>';
                if($page - 1 > 0) $page1left = '<li><a href="index.php?page='.($page - 1).'&num='.(int)$_GET['num'].'">'.($page - 1).'</a></li>';

                if($page + 5 <= $total) $page5right = '<li><a href="index.php?page='.($page + 5).'&num='.(int)$_GET['num'].'">'.($page + 5).'</a></li>';
                if($page + 4 <= $total) $page4right = '<li><a href="index.php?page='.($page + 4).'&num='.(int)$_GET['num'].'">'.($page + 4).'</a></li>';
                if($page + 3 <= $total) $page3right = '<li><a href="index.php?page='.($page + 3).'&num='.(int)$_GET['num'].'">'.($page + 3).'</a></li>';
                if($page + 2 <= $total) $page2right = '<li><a href="index.php?page='.($page + 2).'&num='.(int)$_GET['num'].'">'.($page + 2).'</a></li>';
                if($page + 1 <= $total) $page1right = '<li><a href="index.php?page='.($page + 1).'&num='.(int)$_GET['num'].'">'.($page + 1).'</a></li>';


                if ($page+5 < $total)
                {
                $strtotal = '<li><p class="nav-point">...</p></li><li><a href="index.php?page='.$total.'">'.$total.'</a></li>';
                }else
                {
                $strtotal = "";
                }

                if ($total > 1)
                {
                echo '
                <div class="pstrnav">
                    <ul>
                        ';
                        echo $pstr_prev.$page5left.$page4left.$page3left.$page2left.$page1left."<li><a class='pstr-active' href='index.php?page=".$page."'>".$page."</a></li>".$page1right.$page2right.$page3right.$page4right.$page5right.$strtotal.$pstr_next;
                        echo '
                    </ul>
                </div>
                ';
                }
                ?>
</ul>
            </center>

            <br>
            <p>Выводить по: <a href="/index.php?num=6&page=<?php echo (int)$_GET['page']; ?>">6</a> <a href="/index.php?num=9&page=<?php echo (int)$_GET['page']; ?>">9</a> <a href=""></a> <a href="/index.php?num=12&page=<?php echo (int)$_GET['page']; ?>">12</a> </p>


        </div>

    </section>

    <!--Присоединяем подвал-->
    <?php
    include("include/footer.php");
    ?>

</div>
</body>
</html>