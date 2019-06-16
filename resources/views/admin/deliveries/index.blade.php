@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список доставок @endslot
        @slot('active') Службы доставки @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.delivery.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.delivery.create')}}">Создать службу доставки</a>
    <hr>
        @include('admin.deliveries.partials.filter')
    <hr>

    @foreach($deliveries as $delivery)
        <form id="form-{{$delivery->id}}" method="post" action="{{route('admin.delivery.update', $delivery)}}">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
        </form>
    @endforeach

    <table class="table table-striped">
        <thead>
            <th>Название</th>
            <th>Порядок</th>
            <th>Изменено</th>
            <th>Редактировать</th>
            <th>Применить</th>
            <th>Удалить</th>
        </thead>

        <tbody>
            @forelse($deliveries as $delivery)
                <tr>
                    <td><input type="text" class="form-control" name="title" value="{{$delivery->title ?? ""}}" form="form-{{$delivery->id}}" required></td>
                    <td><input type="number" class="form-control index-order-input" name="order" value="{{$delivery->order ?? ""}}" form="form-{{$delivery->id}}"></td>
                    <td>{{$delivery->updated_at}}</td>

                    <td>
                        <a href="{{route('admin.delivery.edit', $delivery)}}"><span class="edit-link">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$delivery->id}}"></td>
                    <td><input class="btn btn-danger btn-delete" type="submit" name="delete" value="&#10008;" form="form-{{$delivery->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Службы доставки отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$deliveries->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
