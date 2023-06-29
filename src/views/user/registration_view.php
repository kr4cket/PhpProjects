<?
    $errors = $data ?? [];
?>
<? foreach ($errors as $error) {?>
    <h6><?=$error?></h6>
    <br>
<?}?>
<h2>Регистрация</h2>
<form method="post">
    <input type="text" name="userName" placeholder="Введите имя">
    <br>
    <input type="text" name="userSurname" placeholder="Введите фамилию">
    <br>
    <input type="text" name="userPhone" placeholder="Введите номер телефона">
    <br>
    <input type="text" name="userLogin" placeholder="Введите логин">
    <br>
    <input type="password" name="userPassword" placeholder="Введите пароль">
    <br>
    <input type="submit" value="Регистрация">
</form>