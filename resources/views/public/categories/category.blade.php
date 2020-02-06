@extends('public/layouts/app')

@section('content')
    <div class="container main">

        <div class="item-page blog-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')

            <main>
                <h1>{{$result['title']}}</h1>

                <div class="flex category-desk">
                    {{--Include Slider--}}
                    @isset($result['preview_img'][0])
                        <div class="detail-image">
                            @include('public.partials.previewSlider')
                        </div>
                    @endisset

                    <div class="category-content @if(!isset($result['preview_img'][0])) full-flex-basis @endif">
                        {!! $result['full_content'] !!}
                    </div>
                </div>
                {{-- Properties include --}}
                {{--<div class="col-md-12">--}}
                    {{--@include('public.partials.properties')--}}
                {{--</div>--}}

                @if(!empty($result['children']))
                    <div class="category-child">
                        <h2>Подразделы</h2>
                        <div class="flex category-child-list category-list">
                            @foreach($result['children'] as $children)
                                <a class="list-item" href="{{route('item.showBlogCategory', ['category_slug' => $children['slug']])}}">
                                    <div class="list-item-title">
                                        {{$children['title']}}
                                    </div>
                                    <div class="wrap-image-list flex">
                                        @if(isset($children['preview_img'][0]))
                                            <img class="list-item-img" src="{{$children['preview_img'][0]['MIDDLE']}}" alt="{{$children['title']}}" title="{{$children['title']}}"/>
                                        @endif
                                    </div>
                                    <span class="order-button">Перейти в раздел</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </main>

            @isset($items)
                <div class="items" itemscope="" itemtype="https://schema.org/Blog">
                    <h2>Материалы</h2>
                    @foreach($items as $item)
                        <div class="item-blog" itemprop="blogPost" itemscope="" itemtype="https://schema.org/BlogPosting">
                            <h3 itemprop="name"><a itemprop="url" href="{{route('item.showBlogItem', ['category_slug' => $result['slug'], 'item_slug' => $item['slug']])}}">{{$item['title']}}</a></h3>
                            @if(isset($item['preview_img'][0]['MIDDLE']))
                                <a class="blog-item-img-link" href="{{route('item.showBlogItem', ['category_slug' => $result['slug'], 'item_slug' => $item['slug']])}}">
                                    <img itemprop="thumbnailUrl" class="blog-item-img" src="{{$item['preview_img'][0]['MIDDLE']}}" alt="{{$item['title']}}" title="{{$item['title']}}"/>
                                </a>
                            @endif
                            <p class="item-blog-desc">{{$item['description'] ?? ""}}</p>
                            <a class="readmore" href="{{route('item.showBlogItem', ['category_slug' => $result['slug'], 'item_slug' => $item['slug']])}}">Подробнее</a>
                        </div>
                    @endforeach
                </div>
            @endisset

            <div class="pagination-wrap">
                <ul class="pagination">
                    {!!$itemsLink!!}
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
