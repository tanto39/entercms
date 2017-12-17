@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title')Создать меню@endslot
        @slot('parent') Меню @endslot
        @slot('parentlink') admin.menu.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.menu.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.menus.partials.form')
    </form>

@endsection