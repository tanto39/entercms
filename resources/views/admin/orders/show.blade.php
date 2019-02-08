@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Просмотр заказа @endslot
        @slot('parent') Заказы @endslot
        @slot('parentlink') admin.order.index @endslot
        @slot('active') Просмотр @endslot
    @endcomponent

    <hr>

    <div class="order-show">
        <hr>
        <div class="order-title">
            <h4>Название, артикул</h4>
            <div>{{$order->title ?? ""}}</div>
        </div>

        <hr>
        <div class="order-title">
            <h4>Состояние заказа</h4>
            <div>{{$order->status_order ?? ""}}</div>
        </div>

        <hr>
        <div class="order-price">
            <h4>Сумма заказа</h4>
            <div>{{$order->price ?? ""}}</div>
        </div>

        <hr>
        <div class="order-full-content">
            <h4>Состав заказа</h4>
            <div>{!! $order->full_content ?? "" !!}</div>
        </div>

    </div>

@endsection