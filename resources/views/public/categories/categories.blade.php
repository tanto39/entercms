@extends('layouts/app')

@section('content')
    <main class="container">
        <h1>Категории</h1>

        @foreach($result as $category)
            <h3><a href="{{route('item.showBlogCategory', ['category_slug' => $category->slug])}}">{{$category->title}}</a></h3>
        @endforeach
    </main>
@endsection
