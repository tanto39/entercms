@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список пользоваетлей @endslot
        @slot('active') Пользователи @endslot
    @endcomponent

    <hr>
        @component('admin.components.search')
            @slot('searchRoute') admin.user.index @endslot
            @slot('searchText') {{$searchText}} @endslot
        @endcomponent
    <hr>
        <a class="btn btn-default" href="{{route('admin.user.create')}}">Создать пользователя</a>
    <hr>
        @include('admin.users.partials.filter')
    <hr>

    @foreach($users as $user)
        <form id="form-{{$user->id}}" method="post" action="{{route('admin.user.update', $user)}}">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
        </form>
    @endforeach

    <table class="table table-striped">
        <thead>
            <th>Имя</th>
            <th>Email</th>
            <th>Изменено</th>
            <th>Админ</th>
            <th>Редактировать</th>
            <th>Применить</th>
            <th>Удалить</th>
        </thead>

        <tbody>
            @forelse($users as $user)
                <tr>
                    <td><input type="text" class="form-control" name="name" value="{{$user->name or ""}}" form="form-{{$user->id}}" required></td>
                    <td><input type="email" class="form-control" name="email" value="{{$user->email or ""}}" form="form-{{$user->id}}"></td>
                    <td>{{$user->updated_at}}</td>
                    <td>
                        <select style="width: 80px; padding: 0 10px; font-size:2em;" class="form-control" name="is_admin" form="form-{{$user->id}}">
                            <option value="1" @if($user->is_admin == 1) selected="" @endif><span style="color: #008000">&#10004;</span></option>
                            <option value="0" @if($user->is_admin == 0) selected="" @endif><span style="color: #ff0013">&#10006;</span></option>
                        </select>
                    </td>
                    <td>
                        <a href="{{route('admin.user.edit', $user)}}"><span style="font-size:2em">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$user->id}}"></td>
                    <td><input class="btn btn-danger" style="font-size: 2em;padding: 2px 5px; line-height: 1;" type="submit" name="delete" value="&#10008;" form="form-{{$user->id}}"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Пользователи отсутствуют
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <ul class="pagination">
                        {{$users->links()}}
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection

<script type="text/javascript">

</script>