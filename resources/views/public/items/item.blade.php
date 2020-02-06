@extends('public/layouts/app')

@section('content')
    <div class="container main">

        <div class="item-page blog-item">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')

            <main itemscope="" itemtype="https://schema.org/Article">
                <h1 itemprop="headline">{{$result['title']}}</h1>

                {{--Include Slider--}}
                <div class="detail-image center-block">
                    @include('public.partials.previewSlider')
                </div>

                <div itemprop="articleBody">
                    {!! $result['full_content'] !!}
                </div>

            </main>

            @if($showReviews == "Y")
                {{-- Reviews include --}}
                @include('public.partials.reviews')
            @endif
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
