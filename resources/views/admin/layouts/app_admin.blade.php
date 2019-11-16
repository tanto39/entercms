<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EnterCMS</title>

    <!-- Styles -->
    <link href="{{ asset('css/admin-app.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app" class="admin-panel">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/admin') }}">EnterCMS</a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Контент</a>
                            <ul class="dropdown-menu" role="menu">
                                @if(Illuminate\Support\Facades\Auth::user()->is_admin == 1)
                                    <li><a href="{{route('admin.category.index')}}">Категории</a></li>
                                @endif
                                <li><a href="{{route('admin.item.index')}}">Материалы</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Магазин</a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{route('admin.order.index')}}">Заказы</a></li>
                                <li><a href="{{route('admin.delivery.index')}}">Службы доставки</a></li>
                            </ul>
                        </li>
                        @if(Illuminate\Support\Facades\Auth::user()->is_admin == 1)
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Свойства</a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{route('admin.property.index')}}">Свойства</a></li>
                                    <li><a href="{{route('admin.propgroup.index')}}">Группы свойств</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Меню</a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{route('admin.menu.index')}}">Меню</a></li>
                                    <li><a href="{{route('admin.menuitem.index')}}">Пункты меню</a></li>                                </ul>
                            </li>
                            <li><a href="{{route('admin.review.index')}}">Отзывы</a></li>
                            <li><a href="{{route('admin.user.index')}}">Пользователи</a></li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">СЕО</a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{route('admin.sitemap.index')}}">Карта сайта</a></li>
                                    <li><a href="{{route('admin.yandexmarket.index')}}">Генерация для Яндекс.Маркет</a></li>
                                    <li><a href="{{route('admin.turbopages.index')}}">Турбостраницы</a></li>
                                    <li><a href="{{route('admin.region.index')}}">Регионы</a></li>
                                </ul>
                            </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Вход</a></li>
                            <li><a href="{{ route('register') }}">Регистрация</a></li>
                        @else
                            <li><a href="/" target="_blank">Перейти на сайт</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Выход
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            @include('admin.partials.msg')
            @yield('content')
        </div>

        <div class="footer">
            <div class="container">
                <p>Система управления контентом разработана <a href="https://enterkursk.ru" target="_blank">EnterKursk.ru</a></p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/admin-app.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    {{-- TinyMCE include --}}
    <script src="{{URL::to('js/tinymce/tinymce.min.js')}}"></script>
    <script>
        var editor_config = {
            extended_valid_elements : "img[*],style[*],list[*],script[*],iframe[*],frame[*]",
            path_absolute : "{{ URL::to('/') }}/",
            selector : "#full_content",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern code"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media code",
            relative_urls: false,
            language: 'ru',
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };

        tinymce.init(editor_config);

    </script>

</body>
</html>
