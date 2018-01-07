<?php
global $menu;
$menu = \App\Http\Controllers\Site\MenuController::createMenuTree();

$uri = $uri = preg_replace("/\?.*/i",'', $_SERVER['REQUEST_URI']);
?>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="{{$result['meta_key'] or META_KEY}}" />
    <meta name="description" content="{{$result['meta_desc'] or META_DESC}}" />

    <title>{{$result['title'] or META_TITLE}}</title>

    <!-- Scripts -->
    <script defer src="{{ asset('js/app.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    <header class="header" id="header">
        <div class="container center-header">
            <div class="row">
                <div class="header-left col-sm-5">

                    <?php if ($uri != "/"):?>
                    <a class="logo" href="/">Название компании</a>
                    <?php else:?>
                    <a class="logo" href="#">Название компании</a>
                    <?php endif;?>

                    <div class="address">г. Курск, ул. Литовская, 95/2</div>
                </div>

                <div class="header-center col-sm-4">
                    <div class="header-phone flex"><i class="glyphicon glyphicon-earphone"></i><span>+7 950 871-54-19</span></div>
                    <a class="header-mail flex" href="mailto:info@tipovoi.ru"><i class="glyphicon glyphicon-envelope"></i><span>info@tipovoi.ru</span></a>
                </div>
                <div class="header-right col-sm-3">
                    <button class="callback" data-target="#modal-callback" data-toggle="modal">Обратный звонок</button>
                </div>
            </div>
        </div>
    </header><!-- .header-->

    <!-- Include menu -->
    @component('public.components.menu')
        @slot('menuSlug') main @endslot
    @endcomponent

    <!-- Include massage -->
    @include('public.partials.msg')

    <!-- Include content -->
    @yield('content')

    <footer class="footer" id="footer">
        <div class="container">
            <div class="row contact" itemscope itemtype="http://schema.org/LocalBusiness" >
                <div class="col-sm-4 footer-block">
                    <div class="fn org" itemprop="name"><span class="category">ООО </span>Типовой сайт</div>
                    <div class="tel" itemprop="telephone">+7 (4712) 2-22-50</div>
                    <div>Адрес: <span itemprop="address">г. Курск, ул. Литовская, 95/2</span></div>
                    <div class="email" itemprop="email">info@tipovoi-sait.ru</div>
                    <div><a href="/">tipovoi.ru</a></div>
                </div>
                <div class="col-sm-4 footer-block"></div>
                <div class="col-sm-4 footer-block">
                    <div>Время работы: <span class="workhours" itemprop="openingHours">Все дни недели 10:00 - 22:00</span></div>
                    <div class="metrica"><img src="/images/metrika.png"></div>
                    <div class="enterkursk">Сайт разработан <a target="_blank" href="https://enterkursk.ru">EnterKursk.ru</a></div>
                </div>
            </div>
        </div>
    </footer>

    <!--кнопки соцсетей-->
    <div class="socbuttons">
        <script defer src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
        <script defer src="//yastatic.net/share2/share.js"></script>
        <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter"></div>
    </div>

    <div class="scroll hidden-xs"><i class="glyphicon glyphicon glyphicon-chevron-up" aria-hidden="true"></i></div>

    <!-- Callback form -->
    <div id="modal-callback" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                    <span class="modal-title">Отправить сообщение</span>
                </div>
                <div class="modal-body">
                    <div class="form-zakaz">
                        <form action="mail.php" method="post">
                            <input class="form-name form-control" type="text" placeholder="Введите имя" required name="name" size="16" />
                            <input class="form-phone form-control" type="tel" placeholder="8**********" required pattern="(\+?\d[- .]*){7,13}" title="Международный, государственный или местный телефонный номер" name="phone" size="16" />
                            <input class="form-mail form-control" type="email" placeholder="email@email" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" name="email" size="16" />
                            <textarea name="mess" class="form-massage" cols="23" rows="8"></textarea>
                            <div class="form-input form-pd"><label>Даю согласие на обработку <a href="#" target="_blank" rel="noopener noreferrer">персональных данных</a>:</label><input class="checkbox-inline" type="checkbox" required="" name="pd" /></div>
                            <label>Защита от спама: введите сумму 2+2:</label><input class="form-control" id="form-capcha" type="number" required name="capcha"/>
                            <input class="btn form-submit" type="submit" name="submit" value="Отправить сообщение" />
                        </form>
                        <div class='message-form'><p>Загрузка...</p></div>
                    </div>
                </div>
                <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Закрыть</button></div>
            </div>
        </div>
    </div>
    <!-- Callback form -->
</div>
</body>
</html>
