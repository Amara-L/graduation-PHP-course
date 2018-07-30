<!--Шапка-->
<header>
       <style>
        li {
            list-style-type: none; /* Убираем маркеры */
        }
    </style>
    <ul>
        <li><b><a href="../index.php"> <h1>Гостевая книга</h1></a></b></li>

        <?php

        if(!empty($_SESSION['id']))
            $id = $_SESSION['id'];

        $sql = "SELECT * FROM `reg` WHERE id = $id";

        $result = mysqli_query($link, $sql);

        if ($result) {

            while($row = mysqli_fetch_array($result)){
                $namesession = $row['name'];
                echo '<li><p> Добро пожаловать, '.$namesession.'! </p><p><a href="/index.php?stop">Выход</a></p></li>';
            }


        } else {

            echo '<li>
            <p><a href="../on_off.php">Вход </a>/

            <a href="../on_off.php">Регистрация </a></p>



            </form></li>';

        }

        ?>


    </ul>
    <br>
    <hr align="center" width="90%" size="2" color=" #D4D4D4 " />
</header>