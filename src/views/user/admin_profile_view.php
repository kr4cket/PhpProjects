<?
    use App\Views\Paginator;
    $userName = $data['user_name'];
    $userSurname = $data['user_surname'];
    $userLogin = $data['login'];
    $reviews = $data['reviews'];
    $message = $data['action'] ?? "";

    $paginator = new Paginator($data['pageCount'], $data['currentPage'], $data['link'] ?? []);
?>

<a href="/logout">Выйти</a>
<h4>Администратор</h4>
<h4>Имя: <?=$userName?></h4>
<h4>Фамилия: <?=$userSurname?></h4>
<h4>Логин: <?=$userLogin?></h4>

<h5><?=$message?></h5>
<h4>Отзывы для модерации</h4>

<? foreach ($reviews as $review) {?>
    <form method="post">
    <p>Имя: <?=$review['name']?> Фамилия: <?=$review['surname']?> Номер телефона: <?=$review['phone_number']?></p>
    <p>Оценка: <?=$review['rating']?></p>
    <h5>Отзыв</h5>
    <?=$review['review']?>
    <br>
    <input type="submit" value="Одобрить" name=<?=$review['id']?>>
    <input type="submit" value="Удалить" name=<?=$review['id']?>>
    </form>
<?}?>

<p><?=$paginator->render(5)?></p>