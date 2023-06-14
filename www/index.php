
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

    function isCorrect($phoneNumber) : bool
    {
        return !preg_match('~^(?:\+7|8)\d{10}$~', $phoneNumber);
    }

    function sendMessage($data)
    {
        $userName = base64_encode($data['name']);
        $userSurname = base64_encode($data['surname']);
        $userMessage = base64_encode($data['message']);
        $userPhone = $data['phoneNumber'];
        $headers = 'Content-Type: text/plain; charset=utf-8' . "\r\n";
        $headers .= 'Content-Transfer-Encoding: base64' . "\r\n";
        $headers .= 'From: ' .ADMIN_EMAIL;
        

        mail(ADMIN_EMAIL, "Отзыв пользователя $userName $userSurname Контактный телефон: $userPhone",$userMessage,
        $headers);

        return array("Сообщение отправлено, спасибо за отзыв =)");
    }

    function validate($data) : array
    {
        $checkUserName = $data['name'];
        $checkUserPhone = rtrim($data['phoneNumber']);
        $userErrors = [];

        if (isCorrect($checkUserPhone)) {
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
    <h5>Фамилия</h5>
    <input type="text" name="surname" placeholder="Фамилия..">
	<br>
	<h5>Имя</h5>
    <input type="text" name="name" required placeholder="Имя..">
	<br>
	<h5>Номер телефона</h5>
    <input type="text" name="phoneNumber" required placeholder="Номер телефона..">
	<br>
	<h5>Отзыв</h5>
    <input type="text" name="message" placeholder="Отзыв..">
	<br>
	<br>
	<input type="submit" name="sendButton" value="Отправить">
</form>


</body>

</html>
