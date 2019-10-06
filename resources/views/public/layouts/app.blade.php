<?php
global $menu;
$menu = \App\Http\Controllers\Site\MenuController::createMenuTree();

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

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="{{$result['meta_key'] ?? META_KEY}}" />
    <meta name="description" content="{{$result['meta_desc'] ?? META_DESC}}" />

    <title>{{$result['title'] ?? META_TITLE}}</title>

    <!-- Scripts -->
    <script defer src="{{ asset('js/app.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    <header class="header" id="header">
        <div class="container flex center-header">
            <div class="header-left">

                <?php if ($uri != "/"):?>
                <a class="logo" href="/">{{COMPANY}}</a>
                <?php else:?>
                <a class="logo" href="#">{{COMPANY}}</a>
                <?php endif;?>

                <div class="address">{{ADDRESS}}</div>
            </div>

            <div class="header-center flex">
                <div class="header-center-wrap">
                    <div class="header-phone flex"><i class="glyphicon glyphicon-earphone"></i><span>{{PHONE}}</span></div>
                    <a class="header-mail flex" href="mailto:{{MAIL}}"><i class="glyphicon glyphicon-envelope"></i><span>{{MAIL}}</span></a>
                </div>
            </div>
            <div class="header-right flex">
                    <div class="header-right-wrap">
                    <button class="callback" data-target="#modal-callback" data-toggle="modal">Обратный звонок</button>
                    <a class="basket-button" href="{{route('item.basket')}}">
                        <i class="glyphicon glyphicon-shopping-cart"></i>
                        <span>Корзина</span>
                    </a>
                </div>
            </div>
        </div>
    </header><!-- .header-->

    <nav class="topmenu-wrap">
        <div class="navbar navbar-default container topmenu">

            <!-- Include menu -->
            @component('public.components.menu')
                @slot('menuSlug') main @endslot
            @endcomponent

            <!-- Include search -->
            @component('public.components.search')
            @endcomponent

            <!-- Authentication Links -->
            {{--@include('public.partials.loginlinks')--}}
        </div>
    </nav>
    <!-- Include massage -->
    @include('public.partials.msg')

    <!-- Include content -->
    @yield('content')

    <footer class="footer" id="footer">
        <div class="container">
            <div class="contact flex" itemscope itemtype="http://schema.org/LocalBusiness" >
                <div class="footer-block">
                    <div class="fn org" itemprop="name"><span class="category">ООО </span>{{COMPANY}}</div>
                    <div class="tel" itemprop="telephone">{{PHONE}}</div>
                    <div>Адрес: <span itemprop="address">{{ADDRESS}}</span></div>
                    <div class="email" itemprop="email">{{MAIL}}</div>
                    <div>Все права защищены</div>
                </div>
                <div class="footer-block text-right">
                    <div>Время работы: <span class="workhours" itemprop="openingHours">Все дни недели 10:00 - 22:00</span></div>
                    <div class="metrica"><img alt="" title="" src="/images/metrika.png"></div>
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
                        <form action="" method="post">
                            <input class="form-name form-control" type="text" placeholder="Введите имя" required name="name" size="16" />
                            <input class="form-phone form-control" type="tel" placeholder="8**********" required pattern="(\+?\d[- .]*){7,13}" title="Международный, государственный или местный телефонный номер" name="phone" size="16" />
                            <input class="form-mail form-control" type="email" placeholder="email@email" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" name="email" size="16" />
                            <textarea name="mess" class="form-massage" cols="23" rows="8"></textarea>
                            <div class="form-input form-pd"><label>Даю согласие на обработку <a href="#" target="_blank" rel="noopener noreferrer">персональных данных</a>:</label><input class="checkbox-inline" type="checkbox" required="" name="pd" /></div>
                            <label>Защита от спама: введите сумму 2+2:</label><input class="form-control" id="form-capcha" type="number" required name="capcha"/>
                            <input class="btn form-submit order-button" type="submit" name="submit" value="Отправить сообщение" />
                        </form>
                        <div class='message-form alert alert-success'><p>Загрузка...</p></div>
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
