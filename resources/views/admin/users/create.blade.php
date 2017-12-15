@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title')Создать пользователя@endslot
        @slot('parent') Пользователи @endslot
        @slot('parentlink') admin.user.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.user.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.users.partials.form')
    </form>


@endsection