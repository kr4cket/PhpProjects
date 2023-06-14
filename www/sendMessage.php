<?php

    CONST ADMIN_EMAIL = "koreshkov200@mail.ru";
    $userName = $_POST['name'];
    $userSurname = $_POST['surname'];
    $userMessage = $_POST['message'];

    mail(ADMIN_EMAIL, "Отзыв пользователя $userName $userSurname", $userMessage);

    echo "message successfuly sended";

?>
