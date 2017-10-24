@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title')Редактировать категорию@endslot
        @slot('parent')Главная@endslot
        @slot('active')Категории@endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.category.update', $category)}}">
        <input type="hidden" name="_method" value="put">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.categories.partials.form')
    </form>


@endsection