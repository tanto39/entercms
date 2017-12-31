@extends('public/layouts/app')

@section('content')
    <div class="container main">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')

            <main class="catalog-categories">
                <h1>Каталог</h1>

                @foreach($result as $category)
                    <div class="catalog-category">
                        <h3><a href="{{route('item.showCatalogCategory', ['category_slug' => $category['slug']])}}">{{$category['title']}}</a></h3>

                        @if(!empty($category['preview_img']))
                            <img src="{{$category['preview_img'][0]['MIDDLE']}}" alt="{{$category['title']}}"/>
                        @endif
                    </div>
                @endforeach
            </main>
        </div>
    </div>
@endsection
