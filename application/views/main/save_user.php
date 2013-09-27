<?php
    if ($_POST['password'] != $_POST['repassword']) {
        exit('Пароль не совпадает!');
    }
    if (isset($_POST['login'])) 
    { 
        $login = $_POST['login']; 
        if ($login == '') 
            { 
                unset($login);
            } 
    } 
    if (isset($_POST['password'])) 
        { 
            $password=$_POST['password']; 
            if ($password =='') 
                { 
                    unset($password);
                } 
        }
 if (empty($login) or empty($password))
    {
    exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
    }
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
 $password = stripslashes($password);
    $password = htmlspecialchars($password);
    $login = trim($login);
    $password = trim($password);
 // подключаемся к базе
  //  include ("bd.php");// файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь 
 // проверка на существование пользователя с таким же логином
    // $result = mysql_query("SELECT id FROM users WHERE login='$login'",$db);
    // $myrow = mysql_fetch_array($result);
    // if (!empty($myrow['id'])) {
    // exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");
    // }
 // если такого нет, то сохраняем данные
    // $result2 = mysql_query ("INSERT INTO users (login,password) VALUES('$login','$password')");
    // Проверяем, есть ли ошибки
 //    if ($result2=='TRUE')
 //    {
 //    echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт. <a href='index.php'>Главная страница</a>";
 //    }
 // else {
 //    echo "Ошибка! Вы не зарегистрированы.";
 //    }
    ?>