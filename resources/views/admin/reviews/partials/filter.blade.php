<form method="post" action="{{route('admin.review.filter')}}">
    {{csrf_field()}}
    <div class="flex">
        <div class="flex-basis-50">
            <label for="item_id-select">Материал</label>
            <select id="item_id-select" class="form-control" name="filter[item_id]">
                <option value="all">Все материалы</option>
                @foreach($items as $itemReview)
                    <option value="{{$itemReview->id}}" @if(isset($filter['item_id']) && $filter['item_id'] == $itemReview->id) selected="" @endif>{{$itemReview->title}}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="active-select">Фильтр по активности</label>
            <select id="active-select" class="form-control" name="filter[published]">
                <option value="all">Все отзывы</option>
                <option value="1" @if(isset($filter['published']) && $filter['published'] == 1) selected="" @endif>Активные</option>
                <option value="0" @if(isset($filter['published']) && $filter['published'] == 0) selected="" @endif>Неактивные</option>
            </select>
        </div>
    </div>

    <label for="sort">Сортировка</label>
    <select id="sort" class="form-control" name="sort">
        <option value="default">По умолчанию</option>
        <option value="dateUp" @if($sort == 'dateUp') selected="" @endif>По дате изменения (сначала новые)</option>
        <option value="dateDown" @if($sort == 'dateDown') selected="" @endif>По дате изменения (сначала старые)</option>
        <option value="title" @if($sort == 'title') selected="" @endif>По алфавиту</option>
    </select>



    <div class="form-buttons">
        <input class="btn btn-primary" type="submit" name="exec" value="Выполнить">
        <input class="btn btn-primary" type="submit" name="reset" value="Сбросить">
    </div>
</form>