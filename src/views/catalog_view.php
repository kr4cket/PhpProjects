<?php
    use App\Views\Paginator;

    $goods = $data['goods'];
    $manufactures = $data['manufactures'];
    $filterParams = $data['link'];
    $paginator = new Paginator($data['pageCount'], $data['currentPage'], $data['link']);
?>
<br>
<form>
    <h4>Сортировка товаров</h4>
    <select name="orderType">
        <option value="">Сортировка</option>
        <option value="orderByName">По имени</option>
        <option value="orderByReviews">По отзывам</option>
        <option value="orderByPriceDownToUp">По увеличению цены</option>
        <option value="orderByPriceUpToDown">По уменьшению цены</option>
    </select>
    <input type="submit" value="Отсортировать">
<br>
    <h4>Фильтрация товаров</h4>
    <select name="manufacture">
        <option value="">Производитель</option>
        <?foreach ($manufactures as $manufacture => $parameter) {?>
            <option value=<?= $parameter['id'] ?>><?= $parameter['manufacture_name'] ?></option>
        <?}?>
    </select>
    <br>
    <input type="text" name="goodFilterName" placeholder="Название товара" value=<?=$filterParams['goodFilterName'] ?? ""?>>
    <br>
    <input type="text" name="minPrice" placeholder="Минимальная цена" value=<?=$filterParams['minPrice'] ?? ""?>>
    <br>
    <input type="text" name="maxPrice" placeholder="Максимальная цена" value=<?=$filterParams['maxPrice'] ?? ""?>>
    <br>
    <input type="submit" value="Отфильтровать">
    <input type="reset" value="Очистить поле">
</form>
<br>
<h4>Каталог товаров</h4>
<table>
    <? if ($data['pageCount'] == 0) {echo('Товары не найдены!');}?>
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

