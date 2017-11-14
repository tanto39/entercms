@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Создать свойство @endslot
        @slot('parent') Свойства @endslot
        @slot('parentlink') admin.property.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.property.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.properties.partials.form')
    </form>


@endsection