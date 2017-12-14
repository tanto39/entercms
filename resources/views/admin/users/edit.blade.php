@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Редактировать пользователя @endslot
        @slot('parent') Пользователи @endslot
        @slot('parentlink') admin.user.index @endslot
        @slot('active') Редактирование @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.user.update', $user)}}">
        <input type="hidden" name="_method" value="put">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.users.partials.form')
    </form>
@endsection