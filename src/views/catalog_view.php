<?php
    use App\Views\Paginator;
    $goods = $data[0];
    $paginator = new Paginator(...array_slice($data,1));
?>
<form>
    <select name="orderType">
        <option value="">Сортировка</option>
        <option value="orderByName">По имени</option>
        <option value="">По отзывам</option>
        <option value="orderByPriceDownToUp">По увеличению цены</option>
        <option value="orderByPriceUpToDown">По уменьшению цены</option>
    </select>
    <input type="submit" value="Отсортировать">
</form>
<br>
<table>
    <?foreach ($goods as $good => $parameter) {?>
    <tr>
        <td><?= $parameter['name'] ?></td>
        <td><?= $parameter['price'] ?></td>
        <td><a href="/goods/<?=$parameter['id']?>">Подробнее</a></td>
    </tr>
    <?}?>
</table>

<p><?=$paginator->render()?></p>
