@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Редактировать свойство @endslot
        @slot('parent') Свойства @endslot
        @slot('parentlink') admin.property.index @endslot
        @slot('active') Редактирование @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.property.update', $property)}}">
        <input type="hidden" name="_method" value="put">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.properties.partials.form')
    </form>
@endsection