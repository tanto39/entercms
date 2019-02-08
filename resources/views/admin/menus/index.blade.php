@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список меню @endslot
        @slot('active') Меню @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.menu.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.menu.create')}}">Создать меню</a>
    <hr>
        @include('admin.menus.partials.filter')
    <hr>

    @foreach($menus as $menu)
        <form id="form-{{$menu->id}}" method="post" action="{{route('admin.menu.update', $menu)}}">
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
            @forelse($menus as $menu)
                <tr>
                    <td><input type="text" class="form-control" name="title" value="{{$menu->title ?? ""}}" form="form-{{$menu->id}}" required></td>
                    <td><input type="number" class="form-control index-order-input" name="order" value="{{$menu->order ?? ""}}" form="form-{{$menu->id}}"></td>
                    <td>{{$menu->updated_at}}</td>
                    <td><input type="text" class="form-control" name="slug" value="{{$menu->slug ?? ""}}" form="form-{{$menu->id}}"></td>
                    <td>
                        <a href="{{route('admin.menu.edit', $menu)}}"><span class="edit-link">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$menu->id}}"></td>
                    <td><input class="btn btn-danger btn-delete" type="submit" name="delete" value="&#10008;" form="form-{{$menu->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Меню отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$menus->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection