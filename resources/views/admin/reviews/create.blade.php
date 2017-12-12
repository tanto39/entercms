@extends('admin.layouts.app_admin')

@section('content')
    @component('admin.components.breadcrumbs')
        @slot('title') Создать отзыв @endslot
        @slot('parent') Отзывы @endslot
        @slot('parentlink') admin.review.index @endslot
        @slot('active') Создание @endslot
    @endcomponent

    <hr>

    <form class="form-horizontal" method="post" action="{{route('admin.review.store')}}">
        {{csrf_field()}}

        {{-- Form include --}}
        @include('admin.reviews.partials.form')
    </form>


@endsection