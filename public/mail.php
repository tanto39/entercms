<?php
if (isset($_POST['name'])) {$name = htmlspecialchars(trim(stripcslashes(strip_tags($_POST['name']))));}
if (isset($_POST['phone'])) {$phone = htmlspecialchars(trim(stripcslashes(strip_tags($_POST['phone']))));}
if (isset($_POST['email'])) {$email = htmlspecialchars(trim(stripcslashes(strip_tags($_POST['email']))));} else {$email = "не указан";}
if (isset($_POST['mess'])) {$mess = htmlspecialchars(trim(stripcslashes(strip_tags($_POST['mess']))));} else {$mess = "пустое сообщение";}
if (isset($_POST['capcha'])) {$capcha = htmlspecialchars(trim(stripcslashes(strip_tags($_POST['capcha']))));}

if ($capcha != 4) {echo "<p>Вы ввели неправильное число - 2+2=4. Попробуйте еще раз!</p>";}
else
{
	if (empty($name) || empty($phone)) 
	{
		echo "<p>Пожалуйста, заполните поля имя и телефон.</p>";
	} 
	else
	{
		$to = ADMIN_EMAIL; /*Укажите ваш адрес электоронной почты*/
		$headers = "Content-type: text/plain; charset = utf-8";
		$subject = "Сообщение с вашего сайта";
		$message = "Имя: $name \nТелефон: $phone \nЭлектронный адрес: $email \nСообщение: $mess";
		$send = mail ($to, $subject, $message, $headers);

		if ($send == 'true')
		{
			echo "<p>Спасибо за отправку вашего сообщения!</p>";
		}
		else 
		{
			echo "<p>Сообщение не отправлено! Попробуйте еще раз!</p>";
		}
	}
}
?>
