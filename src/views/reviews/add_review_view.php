<? if (isset($data['errors'])) {
        foreach ($data['errors'] as $error) { ?>
        <h3><?=$error?></h3>
        <br>
    <? }
    } 
?>
<form method="post">
    <p>Имя</p>
    <input type='text' name="name" required value=<?= $data["name"];?>>
    <br>
    <br>
    <p>Фамилия</p>
    <input type='text' name="surname" required value=<?= $data["surname"];?>>
    <br>
    <br>
    <p>Номер телефона</p>
    <input type='text' name="phoneNumber" required value=<?= $data["phoneNumber"];?>>
    <br>
    <br>
    <p>Отзыв</p>
    <input type='text' name="review" value=<?= $data["review"];?>>
    <br>
    <p>Оценка</p>
    <div>
        <p><input type="radio" value=1 name="rating">1</p>
        <p><input type="radio" value=2 name="rating">2</p>
        <p><input type="radio" value=3 name="rating">3</p>
        <p><input type="radio" value=4 name="rating">4</p>
        <p><input type="radio" value=5 name="rating">5</p>
    </div>
    <br>
    <input type="submit" value="Добавить отзыв">
</form>
