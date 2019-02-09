@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Создать пункт меню @endslot
        @slot('parent') Пункты меню @endslot
        @slot('parentlink') admin.menuitem.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{route('admin.menuitem.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.menuitems.partials.form')
    </form>


@endsection