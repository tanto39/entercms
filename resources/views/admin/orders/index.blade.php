@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список заказов @endslot
        @slot('active') Заказы @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.order.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>

    @if($isAdmin)
        <a class="btn btn-default" href="{{route('admin.order.create')}}">Создать заказ</a>
        <hr>
    @endif

        @include('admin.orders.partials.filter')
    <hr>

    @foreach($orders as $order)
        <form id="form-{{$order->id}}" method="post" action="{{route('admin.order.update', $order)}}">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
        </form>
    @endforeach

    <table class="table table-striped">
        <thead>
            <th>Название</th>
            <th>Состояние</th>
            <th>Изменено</th>
            @if($isAdmin)
                <th>Редактировать</th>
                <th>Применить</th>
                <th>Удалить</th>
            @endif
        </thead>

        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>
                        @if($isAdmin)
                            <input type="text" class="form-control" name="title" value="{{$order->title ?? ""}}" form="form-{{$order->id}}" required>
                        @else
                            <a href="{{route("admin.order.show", $order->id)}}">{{$order->title}}</a>
                        @endif
                    </td>
                    <td>
                        <select class="form-control" name="status_order" @if(!$isAdmin) disabled @endif form="form-{{$order->id}}">
                            @foreach($status_orders as $status_order)
                                <option value="{{$status_order->id}}" @if($order->status_order == $status_order->id) selected="" @endif>{{$status_order->title}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>{{$order->updated_at}}</td>

                    @if($isAdmin)
                        <td>
                            <a href="{{route('admin.order.edit', $order)}}"><span class="edit-link">&#9998;</span></a>
                        </td>
                        <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$order->id}}"></td>
                        <td><input class="btn btn-danger btn-delete" type="submit" name="delete" value="&#10008;" form="form-{{$order->id}}"></td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Заказы отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$orders->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
