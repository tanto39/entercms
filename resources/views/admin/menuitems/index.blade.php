@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список пунктов меню @endslot
        @slot('active') Пункты меню @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.menuitem.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.menuitem.create')}}">Создать пункт меню</a>
    <hr>
        @include('admin.menuitems.partials.filter')
    <hr>

    @foreach($menuitems as $menuitem)
        <form id="form-{{$menuitem->id}}" method="post" action="{{route('admin.menuitem.update', $menuitem)}}">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
        </form>
    @endforeach

    <table class="table table-striped">
        <thead>
            <th>Название</th>
            <th>Порядок</th>
            <th>Изменено</th>
            <th>Симовльный код</th>
            <th>Редактировать</th>
            <th>Применить</th>
            <th>Удалить</th>
        </thead>

        <tbody>
            @forelse($menuitems as $menuitem)
                <tr>
                    <td><input type="text" class="form-control" name="title" value="{{$menuitem->title ?? ""}}" form="form-{{$menuitem->id}}" required></td>
                    <td><input type="number" class="form-control index-order-input" name="order" value="{{$menuitem->order ?? ""}}" form="form-{{$menuitem->id}}"></td>
                    <td>{{$menuitem->updated_at}}</td>
                    <td><input type="text" class="form-control" name="slug" value="{{$menuitem->slug ?? ""}}" form="form-{{$menuitem->id}}" required></td>
                    <td>
                        <a href="{{route('admin.menuitem.edit', $menuitem)}}"><span class="edit-link">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$menuitem->id}}"></td>
                    <td><input class="btn btn-danger btn-delete" type="submit" name="delete" value="&#10008;" form="form-{{$menuitem->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Пункты меню отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$menuitems->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
