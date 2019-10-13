<?php
    $menu = \App\Http\Controllers\Site\MenuController::getInstance()->getMenuTree();
?>

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
    <ul class="nav navbar-nav flex">
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
</div>
