@extends('layouts/app')

@section('content')
    <main class="container">
        <h1>{{$result->title}}</h1>
        <div>
            {!! $result->full_content !!}
        </div>
    </main>
@endsection
