@extends('public/layouts/app')
<?php
//dd($result['properties']);
?>
@section('content')
    <div class="container main">

        <div class="item-page product-detail">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')
            <main itemscope itemtype="http://schema.org/Product">
                <h1 itemprop="name">{{$result['title']}}</h1>
                <div class="row">

                    <div class="col-md-8">

                        <div class="top-product-block flex">
                            <div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">Цена:
                                <span itemprop="price">@if(isset($result['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value'])){{$result['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value']}}@else 0 @endif</span>
                                <span>руб.</span>
                                <meta itemprop="priceCurrency" content="RUB">
                            </div>
                            <button class="order-button callback_content" data-target="#modal-zakaz" data-toggle="modal">
                                <i class="glyphicon glyphicon-shopping-cart"></i>
                                <span>Заказать</span>
                            </button>
                        </div>

                        {{-- Properties include --}}
                        @include('public.partials.properties')
                    </div>

                    {{--Include Slider--}}
                    <div class="col-md-4 detail-image">
                        @include('public.partials.previewSlider')
                    </div>
                </div>

                <div class="full_content" itemprop="description">{!! $result['full_content'] !!}</div>
            </main>

            {{-- Reviews include --}}
            @include('public.partials.reviews')

        </div>
    </div>

    <!--Modal order-->
    <div id="modal-zakaz" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                    <span class="modal-title"><h4>Заказ</h4></span>
                </div>
                <div class="modal-body">
                    <div class="form-zakaz">
                        <form method="post" action="/sendorder">
                            {{csrf_field()}}
                            <input class="form-name form-control" type="text" placeholder="Введите имя" required name="name" size="16" />
                            <input class="form-phone form-control" type="tel" placeholder="8**********" required pattern="(\+?\d[- .]*){7,13}" title="Международный, государственный или местный телефонный номер" name="phone" size="16" />
                            <input class="form-mail form-control" type="email" placeholder="email@email" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" name="email" size="16" />
                            <label>Товар:</label>
                            <div class="form-zakaz-product">{{$result['title']}}</div>
                            <input type="hidden" name="title" value="{{$result['title']}}"/>
                            <label>Цена:</label>
                            <div class="form-zakaz-product">@if(isset($result['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value'])){{$result['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value']}}@else 0 @endif</div>
                            <input type="hidden" name="price" value="@if(isset($result['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value'])){{$result['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value']}}@else 0 @endif"/>
                            <div class="form-input form-pd"><label>Даю согласие на обработку <a href="#" target="_blank" rel="noopener noreferrer">персональных данных</a>:</label><input class="checkbox-inline" type="checkbox" required="" name="pd" /></div>
                            <label>Защита от спама: введите сумму 2+2:</label><input class="form-control" id="form-capcha" type="number" required name="capcha"/>
                            <input class="btn form-submit order-button" type="submit" name="submit" value="Сделать заказ" />
                        </form>
                        <div class='message-form alert alert-success'><p>Загрузка...</p></div>
                    </div>
                </div>
                <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Закрыть</button></div>
            </div>
        </div>
    </div>
    <!--Modal order-->

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
