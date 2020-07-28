<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/public/xlsDocParcer/parceExcel.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/public/xlsDocParcer/SimpleXLS.php");

$period = htmlspecialchars(trim(stripcslashes(strip_tags($_POST['period']))));
$date = htmlspecialchars(trim(stripcslashes(strip_tags($_POST['date']))));
$summ = $_POST['summ'];
$files = $_FILES;

$result = \Enterkursk\Parce\parceExcel::execute($period, $files, $summ, $date);
?>
<!--<pre>-->
<!--    --><?php
//        print_r($result);
//    ?>
<!--</pre>-->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta content="telephone=no" name="format-detection">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

    <meta name="keywords" content="" />
    <meta name="description" content="" />

    <title>Формирование справок</title>

    <!-- Scripts -->
    <script defer src="/xlsDocParcer/js/scripts.js"></script>

    <!-- Styles -->
    <link href="/xlsDocParcer/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container wrapper">
    <h1>Скачать результат</h1>
    <p><a download href="/xlsDocParcer/files/<?=$result?>">Скачать справки</a></p>

    <p><a href="/xlsDocParcer/index.php">Вернуться</a></p>

</div>
</body>
</html>