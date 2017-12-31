@extends('public/layouts/app')

@section('content')
    <div class="container main">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')

            <main>
                <h1>{{$result['title']}}</h1>

                @if(!empty($result['preview_img']))
                    <img src="{{$result['preview_img'][0]['MIDDLE']}}" alt="{{$result['title']}}"/>
                @endif

                <article>
                    {!! $result['full_content'] !!}
                </article>
            </main>

            @isset($items)
                <div class="container items">
                    <h2>Товары</h2>
                    @foreach($items as $item)
                        <h3><a href="{{route('item.showProduct', ['category_slug' => $result['slug'], 'item_slug' => $item['slug']])}}">{{$item['title']}}</a></h3>
                    @endforeach
                </div>
            @endisset

            <div class="container pagination-wrap">
                <ul class="pagination">
                    {{$itemsLink}}
                </ul>
            </div>
        </div>
    </div>
@endsection
