<?php

//$uri = preg_replace("/\?.*/i",'', $_SERVER['REQUEST_URI']);

$months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
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

    <title>Формирование справок</title>

    <!-- Scripts -->
    <script defer src="/xlsDocParcer/js/scripts.js"></script>

    <!-- Styles -->
    <link href="/xlsDocParcer/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container wrapper">
    <h1>Формирование справок</h1>

    <div class="parce-form">
        <form method="post" enctype="multipart/form-data" class="form-excel" action="/xlsDocParcer/ajax.php">
            <!--		<input type="hidden" name="MAX_FILE_SIZE" value="100000" />-->

            <div style="margin-top: 20px;">
                <input name="load-file" type="file" multiple="multiple" class="form-load-parce-file form-control"/>
            </div>

            <label for="period">Укажите период</label>
            <input type="text" id="period" class="form-control period" name="period" value="01.07.2020 - 31.08.2020"/>

            <label for="date">Укажите дату выдачи справки</label>
            <input type="text" id="date" class="form-control date" name="date" value="<?=date('d.m.Y')?>"/>

            <h3>Суммы для ветеранов труда</h3>
            <div class="summ-block flex">
                <?php foreach($months as $key=>$month): ?>
                <div class="summ-item">
                    <label for="vt-<?=$key?>"><?=$month?></label>
                    <input type="text" id="vt-<?=$key?>" class="form-control summ-input" name="summ[vt][<?=$month?>]" value=""/>
                </div>
                <?php endforeach; ?>
            </div>

            <h3>Суммы для тружеников тыла</h3>
            <div class="summ-block flex">
                <?php foreach($months as $key=>$month): ?>
                    <div class="summ-item">
                        <label for="tt-<?=$key?>"><?=$month?></label>
                        <input type="text" id="tt-<?=$key?>" class="form-control summ-input" name="summ[tt][<?=$month?>]" value=""/>
                    </div>
                <?php endforeach; ?>
            </div>

            <h3>Суммы для прод товаров</h3>
            <div class="summ-block flex">
                <?php foreach($months as $key=>$month): ?>
                    <div class="summ-item">
                        <label for="vv-<?=$key?>"><?=$month?></label>
                        <input type="text" id="vv-<?=$key?>" class="form-control summ-input" name="summ[vv][<?=$month?>]" value=""/>
                    </div>
                <?php endforeach; ?>
            </div>

            <input type="submit" name="parce" class="btn parce-submit" value="Загрузить"/>
        </form>
    </div>



    <div class="result">

    </div>

</div>
</body>
</html>
