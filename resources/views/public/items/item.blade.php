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
        </div>
    </div>
@endsection
