@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список материалов @endslot
        @slot('active') Материалы @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.item.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.item.create')}}">Создать материал</a>
    <hr>
        @include('admin.items.partials.filter')
    <hr>

    @foreach($items as $item)
        <form id="form-{{$item->id}}" method="post" action="{{route('admin.item.update', $item)}}">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
        </form>
    @endforeach

    <table class="table table-striped">
        <thead>
            <th>Название</th>
            <th>Порядок</th>
            <th>Изменено</th>
            <th>Активность</th>
            <th>Редактировать</th>
            <th>Применить</th>
            <th>Удалить</th>
        </thead>

        <tbody>
            @forelse($items as $item)
                <tr>
                    <td><input type="text" class="form-control" name="title" value="{{$item->title ?? ""}}" form="form-{{$item->id}}" required></td>
                    <td><input type="number" class="form-control index-order-input" name="order" value="{{$item->order ?? ""}}" form="form-{{$item->id}}"></td>
                    <td>{{$item->updated_at}}</td>
                    <td>
                        <select class="form-control select-tick" name="published" form="form-{{$item->id}}">
                            <option value="1" @if($item->published == 1) selected="" @endif>&#10004;</option>
                            <option value="0" @if($item->published == 0) selected="" @endif>&#10006;</option>
                        </select>
                    </td>
                    <td>
                        <a href="{{route('admin.item.edit', $item)}}"><span class="edit-link">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$item->id}}"></td>
                    <td><input class="btn btn-danger btn-delete" type="submit" name="delete" value="&#10008;" form="form-{{$item->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Материалы отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$items->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
