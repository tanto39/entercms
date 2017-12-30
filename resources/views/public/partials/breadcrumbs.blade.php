<?php
$breadcrumbs = \App\Http\Controllers\Site\BreadcrumbController::createBreadcrumbs();
?>

@if(!empty($breadcrumbs))
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $key=>$breadcrumb)
        <li @if($breadcrumb['active'] == 'Y')class="active"@endif>
            @if($breadcrumb['active'] == 'N')
                <a href="{{$breadcrumb['href']}}">{{$breadcrumb['title']}}</a>
            @else
                <span>{{$breadcrumb['title']}}</span>
            @endif
        </li>
        @endforeach
    </ol>
@endif
