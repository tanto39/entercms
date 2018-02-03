@extends('public/layouts.app')

@section('content')
<aside class="section landing-section landing-section-color">
    <div class="container adv-wrap text-center">
        <div class="col-sm-4 adv-item">
            <div class="adv-img adv-img-1"></div>
            <p class="adv-title">Доступные цены</p>
            <p class="adv-desc">Лучшие в Курске цены</p>
        </div>
        <div class="col-sm-4 adv-item">
            <div class="adv-img adv-img-2"></div>
            <p class="adv-title">Высокое качество</p>
            <p class="adv-desc">Только официальные поставщики</p>
        </div>
        <div class="col-sm-4 adv-item">
            <div class="adv-img adv-img-3"></div>
            <p class="adv-title">Многолетний опыт</p>
            <p class="adv-desc">Мы дорожим своим авторитетом</p>
        </div>
    </div>
</aside>
<main class="landing-section" itemscope="" itemtype="https://schema.org/Article">
    <article class="container" itemprop="articleBody">
        <h1 itemprop="headline">{{$result['title']}}</h1>
        @if(!empty($result['preview_img']))
            <img class="image-left" src="{{$result['preview_img'][0]['MIDDLE']}}" alt="{{$result['title']}}" title="{{$result['title']}}" />
        @endif
        {!! $result['full_content'] !!}
    </article>
    <div class="clearfix"></div>
    <button class="callback" data-target="#modal-callback" data-toggle="modal">Обратный звонок</button>
</main>
<section class="section landing-section landing-section-color">
    <div class="container catalog-categories">
        <h2>Каталог товаров</h2>
        <div class="flex category-list">
            @foreach($categories as $category)
                <a class="list-item" href="{{route('item.showCatalogCategory', ['category_slug' => $category['slug']])}}">
                    <div class="list-item-title">
                        {{$category['title']}}
                    </div>
                    <div class="wrap-image-list flex">
                        @if(isset($category['preview_img'][0]))
                            <img class="list-item-img" src="{{$category['preview_img'][0]['MIDDLE']}}" alt="{{$category['title']}}" title="{{$category['title']}}"/>
                        @endif
                    </div>
                    <span class="order-button">Перейти в раздел</span>
                </a>
            @endforeach
        </div>
        <a href="{{route('item.showCatalogCategories')}}" class="order-button order-button-link">Перейти в каталог</a>
    </div>
</section>
<section class="landing-section section-map">
    <h2>Мы на карте</h2>
    <div class="yandex-map">
        <div class="map-load">Загрузка карты...</div>
        <script defer charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3AYWBlETvV_7SlsILrBhnqigPl7yE_Wk5d&amp;width=100%25&amp;height=358&amp;lang=ru_RU&amp;scroll=false"></script>
    </div>
</section>
@endsection