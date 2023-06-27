<h1><?=($data['type'].' '.$data['manufacture'].' '.$data['name']);?></h1>
<br>
<h2><?=($data['price'])?> рублей</h2>
<br>
<h1>Описание продукта<h1>
<br>
<h3><?=($data['description'])?></h3>
<br>
<h1>Наличие:</h1>
<? if (!$data['is_sold_out']) {?>
    <h3>Есть в наличии</h3>
<?} else {?>
    <h3>Нет в наличии</h3>
<?}?>
<br>
<h1>Отзывы</h1>
<a href=<?="/review?productId=".$data['id']?>>Добавить отзыв</a>
<? foreach($data['reviews'] as $review) { ?>
    <div>
        <p><?=$review['name'].' '.$review['surname']?></p>
        <p><?=$review['phone_number']?></p>
        <p><?="Оценка: ".$review['rating']?></p>
        <p><?=$review['review'];}?></p>
        <br>
    </div>


