@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список групп свойств @endslot
        @slot('active') Группы свойств @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.propgroup.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.propgroup.create')}}">Создать группу свойств</a>
    <hr>
        @include('admin.propgroups.partials.filter')
    <hr>

    @foreach($propgroups as $propgroup)
        <form id="form-{{$propgroup->id}}" method="post" action="{{route('admin.propgroup.update', $propgroup)}}">
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
            @forelse($propgroups as $propgroup)
                <tr>
                    <td><input type="text" class="form-control" name="title" value="{{$propgroup->title or ""}}" form="form-{{$propgroup->id}}" required></td>
                    <td><input type="number" class="form-control" style="width: 90px;" name="order" value="{{$propgroup->order or ""}}" form="form-{{$propgroup->id}}"></td>
                    <td>{{$propgroup->updated_at}}</td>
                    <td><input type="text" class="form-control" name="slug" value="{{$propgroup->slug or ""}}" form="form-{{$propgroup->id}}"></td>
                    <td>
                        <a href="{{route('admin.propgroup.edit', $propgroup)}}"><span style="font-size:2em">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$propgroup->id}}"></td>
                    <td><input class="btn btn-danger" style="font-size: 2em;padding: 2px 5px; line-height: 1;" type="submit" name="delete" value="&#10008;" form="form-{{$propgroup->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Группы отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$propgroups->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection

<script type="text/javascript">

</script>