@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Редактировать материал @endslot
        @slot('parent') Материалы @endslot
        @slot('parentlink') admin.item.index @endslot
        @slot('active') Редактирование @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{route('admin.item.update', $item)}}">
        <input type="hidden" name="_method" value="put">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.items.partials.form')
    </form>
@endsection