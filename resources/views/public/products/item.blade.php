@extends('layouts/app')

@section('content')
    <div class="container">

        <div class="item-page">
            {{-- Breadcrumbs include --}}
            @include('public.partials.breadcrumbs')
            <main>
                <h1>{{$result->title}}</h1>
                <div>
                    {!! $result->full_content !!}
                </div>
            </main>
        </div>
    </div>
@endsection
