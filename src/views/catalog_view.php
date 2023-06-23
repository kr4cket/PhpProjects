<?php
    $goods = $data[0];
    $pageCount = $data[1];
    $page = $data[2];
    $order = $data[3];

    if ($order != 'id') {
        $order="&orderType=$order";
    } else {
        $order = '';
    }

    function pagination($page, $pageCount) 
    {
        if ($pageCount < 5){
            $pageArray = [];
            for ($i = 1; $i <= $pageCount; $i++) {
                $pageArray[] = $i;
            }
            return $pageArray;
        }
        if ($page - 2 > 1 && $page+2 < $pageCount) {
            return [1, '...', $page - 2, $page - 1, $page, $page + 1, $page + 2, '...', $pageCount];
        }
        if ($page - 2 > 1 && $page+2 < $pageCount) {
            return [1, '...', $pageCount - 2, $pageCount - 1, $pageCount];
        }
        if ($page + 2 < $pageCount) {
            return [1, 2, 3, '...', $pageCount];
        }
    }
?>
<title>Интернет магазин</title>
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

<?
    $pages = pagination($page, $pageCount);
?>
<p><?
    foreach ($pages as $pageIndex) {
        if ($pageIndex == '...') {
            continue;
        } else { ?>
    <a href="/?page=<?=$pageIndex.$order?>"><?=$pageIndex?></a>
<?    }
    }
?>
</p>
