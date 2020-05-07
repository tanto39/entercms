@extends('public/layouts/app')

@section('content')
    <div class="container main">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')

            @isset($result)
                <div class="col-md-12 items">
                    <h1>Результаты поиска: {{$searchText ?? ""}}</h1>
                    @foreach($result as $item)
                        <div class="item-blog">
                            <h3><a href="{{$item['href']}}">{{$item['title']}}</a></h3>
                            @if(isset($item['preview_img'][0]))
                                <a class="blog-item-img-link" href="{{$item['href']}}">
                                    <img class="blog-item-img" src="{{$item['preview_img'][0]['MIDDLE']}}" alt="{{$item['title']}}" title="{{$item['title']}}"/>
                                </a>
                            @endif
                            <p class="item-blog-desc">{{$item['description'] ?? ""}}</p>
                            <a class="readmore" href="{{$item['href']}}">Подробнее</a>
                        </div>
                    @endforeach
                </div>
            @endisset

            {{-- Pagination include --}}
            @include('public.partials.pagination')
        </div>
    </div>
@endsection
