<?php
$link = mysqli_connect($hostname, $user, $pass, $db) or die("Не могу подключиться к базе данных! Причина:".mysqli_error($link));

mysqli_query($link, "set names 'utf8'");

global $page;
global $total;
//Функция вывода комментариев
function printcomments($page)
{
    global $link;
    global $id;

    $adm = 0;

    if (!empty($id)) {
        $sql = "SELECT admin FROM reg WHERE id =  $id";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        $adm = (int)$row['admin'];
    }
//если не админ то выводим как обычно
    if ($adm == 0) {


        $sql = "SELECT * FROM `comments` WHERE 1 ORDER BY id DESC $page";

        $result = mysqli_query($link, $sql);


        if ($result) {

            while ($row = mysqli_fetch_array($result)) {
                echo '<p><b>' . $row['name'] . '</b><br><br>' . $row['text'] . '</p>';
            }


        }
//если админ - добавляем функции удаления и изменения комментариев
    } elseif ($adm == 1){

        $sql = "SELECT * FROM `comments` WHERE 1 ORDER BY id DESC $page";

        $result = mysqli_query($link, $sql);

        if ($result) {

            while ($row = mysqli_fetch_array($result)) {
                echo '<p><b>' . $row['name'] . '</b><br><br>' . $row['text'] . '</p><br><a href="/index.php?del='.$row['id'].'">Удалить</a><a href="/index.php?upd='.$row['id'].'">Изменить</a> ';
            }


        }


    }else{ //в ином случае выводим как обычно

        $sql = "SELECT * FROM `comments` WHERE 1 ORDER BY id DESC $page";

        $result = mysqli_query($link, $sql);


        if ($result) {

            while ($row = mysqli_fetch_array($result)) {
                echo '<p><b>' . $row['name'] . '</b><br><br>' . $row['text'] . '</p>';
            }


        }

    }

}

//Функция добавления комментария
function insertcomments($textcom, $name='Аноним'){

    global $link;

    $sql = "INSERT INTO `comments` (`name`, `text`)
    VALUES ('{$name}','{$textcom}')";
    if(mysqli_query($link, $sql)){
    }
    else {
        echo 'Ошибка<br/>';
    }

}

function pages(){
    if(!empty($_GET['page'])) {
        $page = $_GET['page'];

        $y = 6 * $page;
        $x = $y - 6;

        return ' LIMIT ' . $x . ', ' . $y;
    } else {
        return ' LIMIT 0, 6';
    }
}

//вычисляем коментарии для вывода
function pages2(){
    global $link;

            $num = 6; // Здесь указываем сколько хотим выводить товаров.
            $page = (int)$_GET['page'];

            $count = mysqli_query($link, "SELECT * FROM `comments` WHERE 1 ORDER BY id DESC ");
            $temp = mysqli_fetch_array($count);

            If ($temp[0] > 0)
            {
                $tempcount = $temp[0];

                // Находим общее число страниц
                $total = (($tempcount - 1) / $num) + 1;
                $total =  intval($total);

                $page = intval($page);

                if(empty($page) or $page < 0) $page = 1;

                if($page > $total) $page = $total;

                // Вычисляем начиная с какого номера
                // следует выводить товары
                $start = $page * $num - $num;

                return $qury_start_num = " LIMIT $start, $num";
            }
}

//удаляем комментарий

function delcomment($idcomm){
global $link;
$sql = "DELETE FROM `comments` WHERE id = $idcomm";

    if(mysqli_query($link, $sql)){} else echo 'Ошибка';

}

//выводим форму для изменения комментария

function updatecomment($idcomm){
    global $link;

    $sql = "SELECT * FROM `comments` WHERE id = $idcomm";

    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $namecom = $row['name'];
    $textcom = $row['text'];

    echo '
    
    <div id="blockmess">
            <form action="" method="get">

                <input type="text" name="name" value="'.$namecom.'" style=" width: 15%; margin-left: 5%;" >
                <br>

                <input type="text" name="textcom" value="'.$textcom.'" style=" width: 90%; height: 5em; margin-left: 5%;" required> <br>

<input type="hidden" name="id" value="'.$idcomm.'" >
<input type="hidden" name="update" value="1">

                <br>
                <input  type="submit" value="Отправить" style="margin-left: 85%">
            </form>
            <br>
        </div>
    
</form>';

}

//изменяем комментарий

function updcomm($idcomm, $namecomm, $textcomm){

    global $link;

    $sql = "UPDATE `comments` SET
            `name` = '{$namecomm}',
            `text` = '{$textcomm}'
          WHERE `id` = {$idcomm}";

    if(mysqli_query($link, $sql)){} else echo 'Ошибка';

}