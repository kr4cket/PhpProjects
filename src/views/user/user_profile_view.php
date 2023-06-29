<?
    $userName = $data['name'];
    $userSurname = $data['surname'];
    $userLogin = $data['login'];
?>

<h2>Пользователь</h2>
<h2>Имя: <?=$userName?></h2>
<h2>Фамилия: <?=$userSurname?></h2>
<h2>Логин: <?=$userLogin?></h2>


<a href="/logout">Выйти</a>