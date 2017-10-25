
<h2>{{$title}}</h2>
<ol class="breadcrumb">
    @if(!empty($parentlink))
        <li><a href="{{route(trim($parentlink))}}">{{$parent}}</a></li>
    @endif
    <li class="active">{{$active}}</li>
</ol>