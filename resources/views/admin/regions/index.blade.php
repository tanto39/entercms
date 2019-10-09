@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список регионов @endslot
        @slot('active') Регионы @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.region.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.region.create')}}">Создать регион</a>
    <hr>
        @include('admin.regions.partials.filter')
    <hr>

    @foreach($regions as $region)
        <form id="form-{{$region->id}}" method="post" action="{{route('admin.region.update', $region)}}">
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
            @forelse($regions as $region)
                <tr>
                    <td><input type="text" class="form-control" name="title" value="{{$region->title ?? ""}}" form="form-{{$region->id}}" required></td>
                    <td><input type="number" class="form-control index-order-input" name="order" value="{{$region->order ?? ""}}" form="form-{{$region->id}}"></td>
                    <td>{{$region->updated_at}}</td>
                    <td><input type="text" class="form-control" name="slug" value="{{$region->slug ?? ""}}" form="form-{{$region->id}}"></td>
                    <td>
                        <a href="{{route('admin.region.edit', $region)}}"><span class="edit-link">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$region->id}}"></td>
                    <td><input class="btn btn-danger btn-delete" type="submit" name="delete" value="&#10008;" form="form-{{$region->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Регионы отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$regions->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection