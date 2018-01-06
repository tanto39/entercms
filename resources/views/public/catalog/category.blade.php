@extends('public/layouts/app')

@section('content')
    <div class="container main">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')

            <main class="col-md-12">
                <h1>{{$result['title']}}</h1>

                {{--Include Slider--}}
                <div class="detail-image center-block">
                    @include('public.partials.previewSlider')
                </div>

                <div class="full-content">{!! $result['full_content'] !!}</div>

                {{-- Properties include --}}
                @include('public.partials.properties')

            </main>

            @isset($items)
                <div class="col-md-12">
                    <h2>Товары</h2>
                    <div class="product-list flex">
                        @foreach($items as $item)
                            <a class="list-item" href="{{route('item.showProduct', ['category_slug' => $result['slug'], 'item_slug' => $item['slug']])}}">
                                <div class="list-item-title">
                                    {{$item['title']}}
                                </div>
                                <div class="wrap-image-list flex">
                                    @if(isset($item['preview_img'][0]))
                                        <img class="list-item-img" src="{{$item['preview_img'][0]['MIDDLE']}}" alt="{{$item['title']}}" title="{{$item['title']}}"/>
                                    @endif
                                </div>
                                <div class="price">Цена:
                                    <span>
                                        @if(isset($item['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value']))
                                            {{$item['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value']}}
                                        @else
                                            0
                                        @endif
                                            руб.
                                    </span>
                                </div>
                                <span class="order-button">Подробнее</span>
                            </a>
                        @endforeach
                    </div>
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
