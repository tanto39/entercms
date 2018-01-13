@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Карта сайта @endslot
        @slot('active') Карта сайта @endslot
    @endcomponent

        <form method="post" action="{{route('admin.sitemap.generate')}}">
            {{csrf_field()}}
            <a class="sitemap-link" href="/sitemap.xml" target="_blank">Посмотреть xml карту сайта</a>

            <div class="form-buttons">
                <input class="btn btn-primary" type="submit" name="save" value="Обновить карту сайта">
            </div>
        </form>
@endsection