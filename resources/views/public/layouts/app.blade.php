<?php
global $menu;
$menu = \App\Http\Controllers\Site\MenuController::createMenuTree();
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
        <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
        <script src="//yastatic.net/share2/share.js"></script>
        <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter"></div>
    </div>

    <div class="scroll"></div>

</body>
</html>
