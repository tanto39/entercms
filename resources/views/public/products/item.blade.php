@extends('public/layouts/app')
@section('content')
    <div class="container main">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')
            <main>
                <h1>{{$result['title']}}</h1>
                <div class="row">
                    <div class="col-md-8">
                        dsfsdfdsf
                    </div>
                    {{--Slider--}}
                    @if(!empty($result['preview_img']))
                        <div class="col-md-4 detail-image">
                            <div class="row">
                                <figure class="detail-image-big flex">
                                    <img class="detail-image-big-img" alt="{{$result['title']}}" src="{{$result['preview_img'][0]['MIDDLE']}}">
                                </figure>
                            </div>

                            <div class="row detail-image-small-block flex">
                                @foreach($result['preview_img'] as $key=>$photo)
                                <a class="detail-image-small-item flex @if($key == 0) active @endif" href="{{$photo['MIDDLE']}}" data-original-src="{{$photo['MIDDLE']}}" data-full-src="{{$photo['FULL']}}" onclick="enterShop.changePicture($(this).data('original-src'), $('.detail-image-big-img'), $(this), event)">
                                    <img class="detail-image-small-item-img" src="{{$photo['SMALL']}}" alt=""/>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="full_content">{!! $result['full_content'] !!}</div>
            </main>

            {{-- Reviews include --}}
            @include('public.partials.reviews')

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
