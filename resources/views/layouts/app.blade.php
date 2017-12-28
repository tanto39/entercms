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
    <meta name="keywords" content="{{$result->meta_key or META_KEY}}" />
    <meta name="description" content="{{$result->meta_desc or META_DESC}}" />

    <title>{{$result->title or META_TITLE}}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <!-- Include menu -->
        @component('public.components.menu')
            @slot('menuSlug') main @endslot
        @endcomponent

        <!-- Include content -->
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
