<?php
    $goods = $data;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Каталог товаров</title>
</head>
<body>
<select>
    <option value="">Сортировка</option>
    <option value="orderByName">По имени</option>
    <option value="">По отзывам</option>
    <option value="orderByPriceDownToUp">По увеличению цены</option>
    <option value="orderByPriceUpToDown">По уменьшению цены</option>
</select>
<br>
<table>
    <?foreach ($goods as $good => $parameter) {?>
    <tr>
        <td><?= $parameter['name'] ?></td>
        <td><?= $parameter['price'] ?></td>
        <td><a href="/goods/show?productId=<?=$parameter['id']?>">Подробнее</a></td>
    </tr>
    <?}?>
</table>
</body>
</html>