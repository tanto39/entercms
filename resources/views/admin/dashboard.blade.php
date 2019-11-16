@extends('admin.layouts.app_admin')

@section('content')
    <h1>Административная панель</h1>
    <div class="flex admin-card">

        <div class="flex-basis-33">
            <a class="alert alert-success" href="{{route('admin.item.index')}}">Материалы</a>
        </div>

        <div class="flex-basis-33">
            <a class="alert alert-success" href="{{route('admin.order.index')}}">Заказы</a>
        </div>

        @if(Illuminate\Support\Facades\Auth::user()->is_admin == 1)
            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.category.index')}}">Категории</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.property.index')}}">Свойства</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.propgroup.index')}}">Группы свойств</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.review.index')}}">Отзывы</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.user.index')}}">Пользователи</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.menu.index')}}">Меню</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.menuitem.index')}}">Типы меню</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.sitemap.index')}}">Карта сайта</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.yandexmarket.index')}}">Генерация для Яндекс.Маркет</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.turbopages.index')}}">Генерация турбостраниц</a>
            </div>

            <div class="flex-basis-33">
                <a class="alert alert-success" href="{{route('admin.delivery.index')}}">Службы доставки</a>
            </div>
        @endif
    </div>
@endsection