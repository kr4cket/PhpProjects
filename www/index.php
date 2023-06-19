<?php

define('PATH_PUBLIC', __DIR__ . DIRECTORY_SEPARATOR);
define('PATH_CODE', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src') . DIRECTORY_SEPARATOR);
define('PATH_CONFIG', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'configs') . DIRECTORY_SEPARATOR);

    spl_autoload_register( function ($className) {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, '../src/'.$className.'.php');
        include $path;
    });

    $messages = [];
    if (isset($_POST['sendButton'])) {
        $form = new \App\Forms\FormClass($_POST);
        $messages = $form->isValid();
        $formData = $form->getFormData();
    } else {
        $form = new \App\Forms\FormClass($_GET);
        $formData = $form->getFormData();
    }

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Обратная связь</title>
</head>
<body>

<?php
    if (isset($messages)) {
        foreach ($messages as $message) {
        echo($message); 
        }
    } 

?>

<h3>Обратная связь</h3>
<form method="post">
    <h5>Фамилия</h5>
    <input type="text" name="surname" placeholder="Фамилия.." value="<?= $formData["surname"];?>">
	<br>
	<h5>Имя</h5>
    <input type="text" name="name" required placeholder="Имя.." value="<?=  $formData["name"];?>">
	<br>
	<h5>Номер телефона</h5>
    <input type="text" name="phoneNumber" required placeholder="Номер телефона.." value="<?= $formData["phoneNumber"];?>">
    <br>
	<h5>Отзыв</h5>
    <select name="list">
        <?php 
            foreach ($formData["list"] as $value) {?>
            <option value=<?= $value['id'];?>><?= $value['theme_type'];?></option>
        <?php }?>
    </select>
    <br>
    <input type="text" name="message" placeholder="Отзыв.." value="<?= $formData["message"];?>">
	<br>
	<br>
	<input type="submit" name="sendButton" value="Отправить">
</form>

</body>

</html>
