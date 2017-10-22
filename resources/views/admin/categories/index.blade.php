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
            <th>Дата публикации</th>
            <th>Активность</th>
            <th>Действие</th>
        </thead>

        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{$category->title}}</td>
                    <td>{{$category->created_at}}</td>
                    <td>{{$category->published}}</td>
                    <td>
                        <a href="{{route('admin.category.edit')}}">Редактировать</a>
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
                    <ul class="pagination pull-right">
                        {{$categories->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection