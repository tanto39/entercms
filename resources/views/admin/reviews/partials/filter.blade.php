<form method="post" action="{{route('admin.review.filter')}}">
    {{csrf_field()}}

    <label for="item_id-select">Материал</label>
    <select id="item_id-select" class="form-control" name="filter[item_id]">
        <option value="all">Все материалы</option>
        @foreach($items as $itemReview)
            <option value="{{$itemReview->id}}" @if(isset($filter['item_id']) && $filter['item_id'] == $itemReview->id) selected="" @endif>{{$itemReview->title}}</option>
        @endforeach
    </select>

    <br>

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