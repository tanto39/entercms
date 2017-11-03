@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Редактировать категорию @endslot
        @slot('parent') Категории @endslot
        @slot('parentlink') admin.category.index @endslot
        @slot('active') Редактирование @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.category.update', $category)}}">
        <input type="hidden" name="_method" value="put">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.categories.partials.form')
    </form>

    @if(isset($category->id))
        <form style="margin-top: 20px;" class="form-horizontal" method="post" action="{{route('admin.category.destroy', $category)}}">
            <input type="hidden" name="_method" value="delete">
            {{csrf_field()}}
            <input class="btn btn-danger" type="submit" value="Удалить">
        </form>
    @endif﻿

@endsection