@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Создать заказ @endslot
        @slot('parent') Заказы @endslot
        @slot('parentlink') admin.order.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.order.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.orders.partials.form')
    </form>


@endsection