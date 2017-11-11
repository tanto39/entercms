@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title')Создать группу свойств@endslot
        @slot('parent') Группы свойств @endslot
        @slot('parentlink') admin.propgroup.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.propgroup.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.propgroups.partials.form')
    </form>


@endsection