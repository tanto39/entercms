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

            {{-- Reviews include --}}
            @include('public.partials.reviews')

        </div>
    </div>
@endsection
