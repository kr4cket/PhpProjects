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
        <td><? echo $parameter['name'] ?></td>
        <td><? echo $parameter['price'] ?></td>
        <td> <input type='button' value="Подробнее" name=<? $parameter['id']?>></td>
    </tr>
    <?}?>
</table>
</body>
</html>