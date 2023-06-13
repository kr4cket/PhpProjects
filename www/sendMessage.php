<?php

    $adminEmail = "koreshkov200@mail.ru";
    $userName = $_POST['name'];
    $userSurname = $_POST['surname'];
    $userMessage = $_POST['message'];

    mail($adminEmail, "Testing", $userMessage);

    echo "message sended";

?>
