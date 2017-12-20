@extends('layouts/app')

@section('content')
    <main class="container">
        <h1>{{$result->title}}</h1>
        <article>
            {!! $result->full_content !!}
        </article>
    </main>
@endsection
