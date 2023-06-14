
<?php
    const ADMIN_EMAIL = "koreshkov200@mail.ru";

    function checkForm($data)
    {
        $messages = [];
        $messages = validate($data);

        if (empty($messages)) {
            $messages = sendMessage($data);
        }

        return $messages;
    }

    function sendMessage($data)
    {
        $userName = $data['name'];
        $userSurname = $data['surname'];
        $userMessage = $data['message'];
        $userPhone = $data['phoneNumber'];
        $headers = 'Content-Type: text/plain; charset=utf-8' . "\r\n";

        mail(ADMIN_EMAIL, "Отзыв пользователя $userName $userSurname Контактный телефон: $userPhone",$userMessage,
        $headers);

        return array("Сообщение отправлено, спасибо за отзыв =)");
    }

    function validate($data) : array
    {
        $checkUserName = $data['name'];
        $checkUserPhone = $data['phoneNumber'];
        $userErrors = [];

        if (empty($checkUserPhone)) {
            array_push($userErrors, "Неправильный формат ввода телефона!");
        }

        if (empty($checkUserName)) {
            array_push($userErrors, "Имя пользователя введено неккоректно!"); 
        }

        if (strlen($checkUserName) < 3) {
            array_push($userErrors, "Имя пользователя должно быть длиннее 3х символов!"); 
        }

        return $userErrors;
    }

    $messages = [];
    if (isset($_POST['sendButton'])) {
        $messages = checkForm($_POST);
    }

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Hello World</title>
</head>
<body>

<?php
    if (isset($messages)) {
        foreach ($messages as &$message) {
        echo($message);
        }
    }
?>

<h3>Обратная связь</h3>

<form method="post">
    <h5>Surname</h5>
    <input type="text" name="surname" placeholder="Enter Surname">
	<br>
	<h5>Name</h5>
    <input type="text" name="name" placeholder="Enter Name">
	<br>
	<h5>Phone number</h5>
    <input type="text" name="phoneNumber"  placeholder="Enter Phone number">
	<br>
	<h5>Message</h5>
    <input type="text" name="message" placeholder="Text message">
	<br>
	<br>
	<input type="submit" name="sendButton"value="Send">
</form>


</body>

</html>
