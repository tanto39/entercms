@extends('public/layouts/app')

@section('content')
    <div class="container main">

        <div class="item-page product-detail">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')
            <main itemscope itemtype="http://schema.org/Product">
                <h1 itemprop="name">{{$result['title']." ".$template->contacts['companyWhere']}}</h1>
                <div class="product">

                    <div class="product-wrap flex">
                        <div class="product-desc @if(empty($result['preview_img'])) full-flex-basis @endif">
                            <div class="top-product-block flex">
                                <div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">Цена:
                                    <span itemprop="price">@if(isset($result['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value'])){{$result['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value']}}@else 0 @endif</span>
                                    <span>руб.</span>
                                    <meta itemprop="priceCurrency" content="RUB">
                                </div>
                                <div class="order-box flex">
                                    <div class="quantity-wrap">
                                        <input class="form-control" type="number" value="1"/>
                                    </div>
                                    <div class="order-button-wrap">
                                        @if ($inBasket == 'Y')
                                            <button class="order-button add-basket-active callback_content" onclick="enterShop.showBasket()"><i class="glyphicon glyphicon-shopping-cart"></i><span>В корзине</span></button>
                                        @else
                                            <button class="order-button callback_content" onclick="enterShop.addToBasket({{$result['id']}}, $('.quantity-wrap input').val())"><i class="glyphicon glyphicon-shopping-cart"></i><span>В корзину</span></button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Properties include --}}
                            @include('public.partials.properties')
                        </div>

                        {{--Include Slider--}}
                        @isset($result['preview_img'][0])
                            <div class="detail-image">
                                @include('public.partials.previewSlider')
                            </div>
                        @endisset
                    </div>

                    <div class="full_content" itemprop="description"><h2>Описание {{$result['title']}}</h2>{!! $result['full_content'] !!}</div>

                </div>
            </main>

            {{-- Reviews include --}}
            @include('public.partials.reviews')

        </div>
    </div>

    <!-- Modal gallery -->
    <div id="blueimp-gallery-carousel" class="blueimp-gallery blueimp-gallery-carousel">
        <div class="slides"></div>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close" style="top: 40px; color: #fff;">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
@endsection
