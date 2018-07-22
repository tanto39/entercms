<?php

$uri = preg_replace("/\?.*/i",'', $_SERVER['REQUEST_URI']);
?>

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

    <title>URL бот</title>

    <!-- Scripts -->
    <script defer src="/urlbot/js/scripts.js"></script>

    <!-- Styles -->
    <link href="/urlbot/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container wrapper">
    <h1>Бот URL</h1>

        <form class="form-vertical" method="post" action="">
            <label for="text_url">Список URL</label>

<textarea id="text_url" class="form-control" name="url_list" rows="15">
http://pristen.rkursk.ru/index.php?date_mod=&title1=&kol=30&sort=date_modified&mun_obr=330&sub_menus_id=23250&num_str=1&id_mat=90846
http://pristen.rkursk.ru/index.php?date_mod=&title1=&kol=30&sort=date_modified&mun_obr=330&sub_menus_id=23250&num_str=1&id_mat=116014
</textarea>

            <label for="num_reload">Количество обновлений страниц</label>
            <input type="text" id="num_reload" class="form-control num_reload" name="num_reload" value="10">

            <input class="btn btn-primary startbot" type="submit" name="startbot" value="Выполнить">
        </form>

    <div class="result">

    </div>

    <iframe id="myiframe" width=100% height=100%></iframe>

</div>
</body>
</html>
