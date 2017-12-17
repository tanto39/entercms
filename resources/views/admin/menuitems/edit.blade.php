@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Редактировать пункт меню @endslot
        @slot('parent') Пункты меню @endslot
        @slot('parentlink') admin.menuitem.index @endslot
        @slot('active') Редактирование @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{route('admin.menuitem.update', $menuitem)}}">
        <input type="hidden" name="_method" value="put">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.menuitems.partials.form')
    </form>
@endsection