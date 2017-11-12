@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Редактировать категорию @endslot
        @slot('parent') Категории @endslot
        @slot('parentlink') admin.category.index @endslot
        @slot('active') Редактирование @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{route('admin.category.update', $category)}}">
        <input type="hidden" name="_method" value="put">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.categories.partials.form')
    </form>
@endsection