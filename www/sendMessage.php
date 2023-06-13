<?php

    $adminEmail = "koreshkov200@mail.ru";
    $userName = $_GET['name'];
    $userSurname = $_GET['surname'];
    $userMessage = $_GET['message'];

    mail($adminEmail, "Testing", $userMessage);

    echo "message sended";

?>
