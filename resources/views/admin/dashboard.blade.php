@extends('admin.layouts.app_admin')

@section('content')
    <h1>Административная панель</h1>
    <div class="flex admin-card">
        <div class="flex-basis-33">
            <a class="alert alert-success" href="{{route('admin.category.index')}}">Категории</a>
        </div>

        <div class="flex-basis-33">
            <a class="alert alert-success" href="{{route('admin.item.index')}}">Материалы</a>
        </div>

        <div class="flex-basis-33">
            <a class="alert alert-success" href="{{route('admin.property.index')}}">Свойства</a>
        </div>

        <div class="flex-basis-33">
            <a class="alert alert-success" href="{{route('admin.propgroup.index')}}">Группы свойств</a>
        </div>

        <div class="flex-basis-33">
            <a class="alert alert-success" href="{{route('admin.order.index')}}">Заказы</a>
        </div>

        <div class="flex-basis-33">
            <a class="alert alert-success" href="{{route('admin.review.index')}}">Отзывы</a>
        </div>

        <div class="flex-basis-33">
            <a class="alert alert-success" href="{{route('admin.user.index')}}">Пользователи</a>
        </div>
    </div>
@endsection