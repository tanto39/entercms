@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title')Создать регион@endslot
        @slot('parent') Регионы @endslot
        @slot('parentlink') admin.region.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.region.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.regions.partials.form')
    </form>

@endsection