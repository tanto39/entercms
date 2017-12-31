@extends('public/layouts/app')

@section('content')
    <div class="container main">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')

            <main>
                <h1>Каталог</h1>

                @foreach($result as $category)
                    <h3><a href="{{route('item.showCatalogCategory', ['category_slug' => $category->slug])}}">{{$category->title}}</a></h3>
                @endforeach
            </main>
        </div>
    </div>
@endsection
