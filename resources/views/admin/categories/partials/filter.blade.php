<form method="post" action="{{route('admin.category.filter')}}">

    <div class="flex">
        <div class="flex-basis-50">
            <label for="category-select">Родительская категория</label>
            {{csrf_field()}}
            <select id="category-select" class="form-control selectpicker" data-live-search="true" name="filter[parent_id]">
                <option value="all">Все категории</option>
                <option value="0" @if(isset($filter['parent_id']) && $filter['parent_id'] == 0) selected="" @endif>Без родителя</option>
                @foreach($parents as $parent)
                    <option value="{{$parent->id}}" @if(isset($filter['parent_id']) && $filter['parent_id'] == $parent->id) selected="" @endif>{{$parent->title}}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="active-select">Фильтр по активности</label>
            <select id="active-select" class="form-control" name="filter[published]">
                <option value="all">Все категории</option>
                <option value="1" @if(isset($filter['published']) && $filter['published'] == 1) selected="" @endif>Активные</option>
                <option value="0" @if(isset($filter['published']) && $filter['published'] == 0) selected="" @endif>Неактивные</option>
            </select>
        </div>
    </div>
    <br>

    <label for="sort">Сортировка</label>
    <select id="sort" class="form-control" name="sort">
        <option value="default">По умолчанию</option>
        <option value="dateUp" @if($sort == 'dateUp') selected="" @endif>По дате изменения (сначала новые)</option>
        <option value="dateDown" @if($sort == 'dateDown') selected="" @endif>По дате изменения (сначала старые)</option>
        <option value="title" @if($sort == 'title') selected="" @endif>По алфавиту</option>
    </select>

    <br>
    <input class="btn btn-primary" type="submit" name="exec" value="Выполнить">
    <input class="btn btn-primary" type="submit" name="reset" value="Сбросить">


</form>