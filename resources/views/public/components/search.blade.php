
@if(strlen($searchText)>0)
    <div class="alert alert-success">Результаты поиска <a href="{{route(trim($searchRoute))}}">Сбросить</a></div>
@endif
<form class="form-inline" method="get" action="{{route(trim($searchRoute))}}">
    <input class="form-control" type="text" @if(!empty($searchText)) value="{{$searchText}}" @endif name="searchText">
    <input class="btn btn-primary" type="submit" value="Поиск">
</form>