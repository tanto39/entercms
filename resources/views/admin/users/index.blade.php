@extends('admin.layouts.app_admin')

@section('content')

    @component('admin.components.breadcrumbs')
        @slot('title') Список пользователей @endslot
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
                    <td><input type="text" class="form-control" name="name" value="{{$user->name ?? ""}}" form="form-{{$user->id}}" required></td>
                    <td><input type="email" class="form-control" name="email" value="{{$user->email ?? ""}}" form="form-{{$user->id}}"></td>
                    <td>{{$user->updated_at}}</td>
                    <td>
                        <select class="form-control select-tick" name="is_admin" form="form-{{$user->id}}">
                            <option value="1" @if($user->is_admin == 1) selected="" @endif><span>&#10004;</span></option>
                            <option value="0" @if($user->is_admin == 0) selected="" @endif><span>&#10006;</span></option>
                        </select>
                    </td>
                    <td>
                        <a href="{{route('admin.user.edit', $user)}}"><span class="edit-link">&#9998;</span></a>
                    </td>
                    <td><input class="btn btn-primary" type="submit" name="saveFromList" value="Ок" form="form-{{$user->id}}"></td>
                    <td><input class="btn btn-danger btn-delete" type="submit" name="delete" value="&#10008;" form="form-{{$user->id}}"></td>
                    <input type="hidden" name="id" value="{{$user->id ?? ""}}" form="form-{{$user->id}}">
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