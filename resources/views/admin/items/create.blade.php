@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Создать материал @endslot
        @slot('parent') Материалы @endslot
        @slot('parentlink') admin.item.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{route('admin.item.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.items.partials.form')
    </form>


@endsection