@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Создать службу доставки @endslot
        @slot('parent') Службы доставки @endslot
        @slot('parentlink') admin.delivery.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{route('admin.delivery.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.deliveries.partials.form')
    </form>


@endsection