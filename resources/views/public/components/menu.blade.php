<?php
    global $menu;
    //dd($menu);
?>

<nav class="topmenu-wrap">
    <div class="navbar navbar-default container topmenu">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @if(!empty($menu))
                    @foreach($menu as $slug=>$menuBlock)
                        @if($slug == $menuSlug)
                            @foreach($menuBlock as $menuItemId=>$menuitem)
                                @if(isset($menuitem['children']))
                                    <li class="dropdown">
                                        <a @if($menuitem['active'] == 'Y') class="active" href="#" @else href="{{$menuitem['href']}}" @endif>{{$menuitem['title']}}</a>
                                        <ul class="dropdown-menu">
                                            @foreach($menuitem['children'] as $children)
                                                <li><a @if($children['active'] == 'Y') class="active" href="#" @else href="{{$children['href']}}" @endif>{{$children['title']}}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li><a @if($menuitem['active'] == 'Y') class="active" href="#" @else href="{{$menuitem['href']}}" @endif>{{$menuitem['title']}}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @guest
                <li><a href="{{ route('login') }}">Вход</a></li>
                <li><a href="{{ route('register') }}">Регистрация</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Logout
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
