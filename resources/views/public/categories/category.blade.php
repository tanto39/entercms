@extends('layouts/app')

@section('content')
    <div class="container">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')

            <main>
                <h1>{{$result->title}}</h1>
                <article>
                    {!! $result->full_content !!}
                </article>
            </main>

            @isset($items)
                <div class="container items">
                    <h2>Материалы</h2>
                    @foreach($items as $item)
                        <h3><a href="{{route('item.showBlogItem', ['category_slug' => $result->slug, 'item_slug' => $item->slug])}}">{{$item->title}}</a></h3>
                    @endforeach
                </div>
            @endisset

            <div class="container pagination-wrap">
                <ul class="pagination">
                    {{$items->links()}}
                </ul>
            </div>
        </div>
    </div>
@endsection
