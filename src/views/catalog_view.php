<?php
    use App\Views\Paginator;

    $goods = $data['goods'];
    $paginator = new Paginator($data['pageCount'], $data['currentPage'], $data['link']);
?>
<form>
    <select name="orderType">
        <option value="">Сортировка</option>
        <option value="orderByName">По имени</option>
        <option value="orderByReviews">По отзывам</option>
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
    <?}
    ?>
</table>

<p><?=$paginator->render(5)?></p>

