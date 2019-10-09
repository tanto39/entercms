@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Редактировать регион @endslot
        @slot('parent') Регионы @endslot
        @slot('parentlink') admin.region.index @endslot
        @slot('active') Редактирование @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.region.update', $region)}}">
        <input type="hidden" name="_method" value="put">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.regions.partials.form')
    </form>
@endsection