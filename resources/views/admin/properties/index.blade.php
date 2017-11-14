@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список свойств @endslot
        @slot('active') Свойства @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.property.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.property.create')}}">Создать свойство</a>
    <hr>
        @include('admin.properties.partials.filter')
    <hr>

    @foreach($properties as $property)
        <form id="form-{{$property->id}}" method="post" action="{{route('admin.property.update', $property)}}">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
        </form>
    @endforeach

    <table class="table table-striped">
        <thead>
            <th>Название</th>
            <th>Порядок</th>
            <th>Изменено</th>
            <th>Символьный код</th>
            <th>Редактировать</th>
            <th>Применить</th>
            <th>Удалить</th>
        </thead>

        <tbody>
            @forelse($properties as $property)
                <tr>
                    <td><input type="text" class="form-control" name="title" value="{{$property->title or ""}}" form="form-{{$property->id}}" required></td>
                    <td><input type="number" class="form-control" style="width: 90px;" name="order" value="{{$property->order or ""}}" form="form-{{$property->id}}"></td>
                    <td>{{$property->updated_at}}</td>
                    <td><input type="text" class="form-control" name="slug" value="{{$property->slug or ""}}" form="form-{{$property->id}}"></td>
                    <td>
                        <a href="{{route('admin.property.edit', $property)}}"><span style="font-size:2em">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$property->id}}"></td>
                    <td><input class="btn btn-danger" style="font-size: 2em;padding: 2px 5px; line-height: 1;" type="submit" name="delete" value="&#10008;" form="form-{{$property->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Свойства отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$properties->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
