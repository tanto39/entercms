@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Редактировать группу свойств @endslot
        @slot('parent') Группы свойств @endslot
        @slot('parentlink') admin.propgroup.index @endslot
        @slot('active') Редактирование @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.propgroup.update', $propgroup)}}">
        <input type="hidden" name="_method" value="put">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.propgroups.partials.form')
    </form>
@endsection