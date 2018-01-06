@extends('public/layouts/app')

@section('content')
    <div class="container main">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')

            <main>
                <h1>{{$result['title']}}</h1>

                {{--Include Slider--}}
                <div class="col-md-4 detail-image">
                    @include('public.partials.previewSlider')
                </div>

                <article class="col-md-8">
                    {!! $result['full_content'] !!}
                </article>

                {{-- Properties include --}}
                {{--<div class="col-md-12">--}}
                    {{--@include('public.partials.properties')--}}
                {{--</div>--}}

            </main>

            @isset($items)
                <div class="col-md-12 items">
                    <h2>Материалы</h2>
                    @foreach($items as $item)
                        <div class="item-blog">
                            <h3><a href="{{route('item.showBlogItem', ['category_slug' => $result['slug'], 'item_slug' => $item['slug']])}}">{{$item['title']}}</a></h3>
                            @if(isset($item['preview_img'][0]))
                                <a class="blog-item-img-link" href="{{route('item.showBlogItem', ['category_slug' => $result['slug'], 'item_slug' => $item['slug']])}}">
                                    <img class="blog-item-img" src="{{$item['preview_img'][0]['MIDDLE']}}" alt="{{$item['title']}}" title="{{$item['title']}}"/>
                                </a>
                            @endif
                            <p class="item-blog-desc">{{$item['description'] or ""}}</p>
                            <a class="readmore" href="{{route('item.showBlogItem', ['category_slug' => $result['slug'], 'item_slug' => $item['slug']])}}">Подробнее</a>
                        </div>
                    @endforeach
                </div>
            @endisset

            <div class="col-md-12 pagination-wrap">
                <ul class="pagination">
                    {{$itemsLink}}
                </ul>
            </div>
        </div>
    </div>

    <div id="blueimp-gallery-carousel" class="blueimp-gallery blueimp-gallery-carousel">
        <div class="slides"></div>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close" style="top: 40px; color: #fff;">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
@endsection
