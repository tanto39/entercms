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
    <form class="form-inline" method="post" action="{{route('admin.category.filter')}}">
        <label for="category-select">Родительская категория</label>
        {{csrf_field()}}
        <select id="category-select" class="form-control" name="categorySelect">
            <option value="0">Все категории</option>
            @foreach($parents as $parent)
                <option value="{{$parent->id}}" @if($filterCategory == $parent->id) selected="" @endif>{{$parent->title}}</option>
            @endforeach
        </select>
        <label for="active-select">Фильтр по активности</label>
        <select id="active-select" class="form-control" name="activeSelect">
            <option value="all">Все категории</option>
            <option value="active" @if($filterActive == 'Y') selected="" @endif>Активные</option>
            <option value="noactive" @if($filterActive == 'N') selected="" @endif>Неактивные</option>
        </select>
        <input class="btn btn-primary" type="submit" value="Выполнить">
    </form>
    <hr>

    <table class="table table-striped">
        <thead>
            <th>Название</th>
            <th>Порядок</th>
            <th>Изменено</th>
            <th>Активность</th>
            <th>Редактировать</th>
        </thead>

        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{$category->title}}</td>
                    <td>{{$category->order or ""}}</td>
                    <td>{{$category->updated_at}}</td>
                    <td><span style="font-size:2em; color:@if($category->published == 1) #008000 @else #ff0000 @endif">&#10004;</span></td>
                    <td>
                        <a href="{{route('admin.category.edit', $category)}}"><span style="font-size:2em">&#9998;</span></a>
                    </td>
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