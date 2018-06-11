@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Генерация турбостраниц для яндекса @endslot
        @slot('active') Турбостраницы @endslot
    @endcomponent

        <form method="post" action="{{route('admin.turbopages.generate')}}">
            {{csrf_field()}}
            <a class="sitemap-link" href="/turbopages.xml" target="_blank">Посмотреть RSS с турбостраницами</a>

            <div class="form-buttons">
                <input class="btn btn-primary" type="submit" name="save" value="Обновить RSS турбостраниц">
            </div>
        </form>
@endsection