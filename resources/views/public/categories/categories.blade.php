@extends('layouts/app')

@section('content')
    <div class="container">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')
            <main>
                <h1>Категории</h1>

                @foreach($result as $category)
                    <h3><a href="{{route('item.showBlogCategory', ['category_slug' => $category->slug])}}">{{$category->title}}</a></h3>
                @endforeach
            </main>
        </div>
    </div>
@endsection
