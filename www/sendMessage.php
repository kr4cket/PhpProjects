<?php

    CONST ADMIN_EMAIL = "koreshkov200@mail.ru";
    $userName = $_POST['name'];
    $userSurname = $_POST['surname'];
    $userMessage = $_POST['message'];
    $userPhone = $_POST['phonenumber'];

    if(empty($userPhone)) {
        echo "please, enter your Phone Number";
    } else {
    	mail(ADMIN_EMAIL, "Отзыв пользователя $userName $userSurname", $userMessage);
    	echo "message successfuly sended";
    }

?>
