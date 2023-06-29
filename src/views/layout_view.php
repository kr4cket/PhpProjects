<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?=$templateData['head']?></title>
</head>
<body>
    <header>
        <a href="/">На главную</a>
        <a href=/goods>Добавить товар</a>
        <?if (empty($_COOKIE)) {?>
            <a href=/registration>Регистрация</a>
            <a href=/authorization>Авторизация</a>
        <?} else{?> <a href=/profile>Профиль</a>
        <?}?>
    </header>
        <?=$templateData['body'];?>
    <footer>
        <p>ООО "Магазин", все права защищены</p>
    </footer>
</body>
</html>