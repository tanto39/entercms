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
                    <td><input type="text" class="form-control" name="title" value="{{$property->title ?? ""}}" form="form-{{$property->id}}" required></td>
                    <td><input type="number" class="form-control index-order-input" name="order" value="{{$property->order ?? ""}}" form="form-{{$property->id}}"></td>
                    <td>{{$property->updated_at}}</td>
                    <td><input type="text" class="form-control" name="slug" value="{{$property->slug ?? ""}}" form="form-{{$property->id}}"></td>
                    <td>
                        <a href="{{route('admin.property.edit', $property)}}"><span class="edit-link">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$property->id}}"></td>
                    <td><input class="btn btn-danger btn-delete" type="submit" name="delete" value="&#10008;" form="form-{{$property->id}}"></td>
                    <input type="hidden" name="prop_kind" value="{{$property->prop_kind ?? ""}}" form="form-{{$property->id}}">
                    <input type="hidden" name="category_id" value="{{$property->category_id ?? ""}}" form="form-{{$property->id}}">
                    <input type="hidden" name="old_category_id" value="{{$property->category_id ?? ""}}" form="form-{{$property->id}}">
                    <input type="hidden" name="id" value="{{$property->id ?? ""}}" form="form-{{$property->id}}">
                    <input type="hidden" name="group_id" value="{{$property->group_id ?? ""}}" form="form-{{$property->id}}">
                    <input type="hidden" name="old_title" value="{{$property->title ?? ""}}" form="form-{{$property->id}}">
                    <input type="hidden" name="old_order" value="{{$property->order ?? ""}}" form="form-{{$property->id}}">
                    <input type="hidden" name="old_type" value="{{$property->type ?? ""}}" form="form-{{$property->id}}">
                    <input type="hidden" name="type" value="{{$property->type ?? ""}}" form="form-{{$property->id}}">
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
