@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Генерация выгрузки Яндекс.Маркет @endslot
        @slot('active') Генерация выгрузки Яндекс.Маркет @endslot
    @endcomponent

        <form method="post" action="{{route('admin.yandexmarket.generate')}}">
            {{csrf_field()}}
            <a class="sitemap-link" href="/yandexmarket.yml" target="_blank">Посмотреть yml</a>

            <div class="form-buttons">
                <input class="btn btn-primary" type="submit" name="save" value="Сгенерировать">
            </div>
        </form>
@endsection