@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title')Список категорий@endslot
        @slot('parent')Главная@endslot
        @slot('active')Категории@endslot
    @endcomponent

    <hr>
    <a class="btn btn-default" href="{{route('admin.category.create')}}">Создать категорию</a>
    <hr>

    <table class="table table-striped">
        <thead>
            <th>Название</th>
            <th>Порядок</th>
            <th>Дата публикации</th>
            <th>Активность</th>
            <th>Редактировать</th>
            <th>Удалить</th>
        </thead>

        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{$category->title}}</td>
                    <td>{{$category->order or ""}}</td>
                    <td>{{$category->created_at}}</td>
                    <td><span style="font-size:2em; color:@if($category->published == 1) #008000 @else #ff0000 @endif">&#10004;</span></td>
                    <td>
                        <a href="{{route('admin.category.edit', $category)}}"><span style="font-size:2em">&#9998;</span></a>
                    </td>
                    <td>
                        <a href="{{route('admin.category.destroy', $category)}}"><span style="font-size:2em">&#10005;</span></a>
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