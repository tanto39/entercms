<form method="post" action="{{route('admin.category.filter')}}">

    <div style="display: flex;">
        <div style="flex-basis: 50%; padding-right: 20px;">
            <label for="category-select">Родительская категория</label>
            {{csrf_field()}}
            <select id="category-select" class="form-control" name="categorySelect">
                <option value="0">Все категории</option>
                @foreach($parents as $parent)
                    <option value="{{$parent->id}}" @if($filterCategory == $parent->id) selected="" @endif>{{$parent->title}}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="active-select">Фильтр по активности</label>
            <select id="active-select" class="form-control" name="activeSelect">
                <option value="all">Все категории</option>
                <option value="Y" @if($filterActive == 'Y') selected="" @endif>Активные</option>
                <option value="N" @if($filterActive == 'N') selected="" @endif>Неактивные</option>
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