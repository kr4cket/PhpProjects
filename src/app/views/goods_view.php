
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><? echo($data['name']) ?></title>
</head>
<body>
    <h1><? echo($data['type'].' '.$data['manufacture'].' '.$data['name']);?><h1>
    <br>
    <h2><? echo($data['price']) ?> рублей<h2>
    <br>
    <h1>Описание продукта<h1>
    <br>
    <h3><? echo($data['description']) ?><h3>
    <br>
    <h1>Наличие:<h1>
    <? if (!$data['is_sold_out']) {?>
        <h3>Есть в наличии</h3>
    <?} else {?>
        <h3>Нет в наличии</h3>
    <?}?>
    <br>
    <h1>Отзывы<h1>
</body>
</html>