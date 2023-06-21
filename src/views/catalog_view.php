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
<table>
    <?foreach ($goods as $good => $parameter) {?>
    <tr>
        <td><?= $parameter['name'] ?></td>
        <td><?= $parameter['price'] ?></td>
        <td><a href="/goods/<?=$parameter['id']?>">Подробнее</a></td>
    </tr>
    <?}?>
</table>
</body>
</html>