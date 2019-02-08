@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список категорий @endslot
        @slot('active') Категории @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.category.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.category.create')}}">Создать категорию</a>
    <hr>
        @include('admin.categories.partials.filter')
    <hr>

    @foreach($categories as $category)
        <form id="form-{{$category->id}}" method="post" action="{{route('admin.category.update', $category)}}">
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
            @forelse($categories as $category)
                <tr>
                    <td><input type="text" class="form-control" name="title" value="{{$category->title ?? ""}}" form="form-{{$category->id}}" required></td>
                    <td><input type="number" class="form-control index-order-input" name="order" value="{{$category->order ?? ""}}" form="form-{{$category->id}}"></td>
                    <td>{{$category->updated_at}}</td>
                    <td>
                        <select class="form-control select-tick" name="published" form="form-{{$category->id}}">
                            <option value="1" @if($category->published == 1) selected="" @endif>&#10004;</option>
                            <option value="0" @if($category->published == 0) selected="" @endif>&#10006;</option>
                        </select>
                    </td>
                    <td>
                        <a href="{{route('admin.category.edit', $category)}}"><span class="edit-link">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$category->id}}"></td>
                    <td><input class="btn btn-danger btn-delete" type="submit" name="delete" value="&#10008;" form="form-{{$category->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Категории отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$categories->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
