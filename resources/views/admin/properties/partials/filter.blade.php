<form method="post" action="{{route('admin.property.filter')}}">
    {{csrf_field()}}
    <div class="flex">

        <div class="flex-basis-33">
            <label for="kind-select">Вид</label>
            <select id="kind-select" class="form-control" name="filter[prop_kind]">
                <option value="all">Все виды</option>
                @foreach($propKinds as $propKind)
                    <option value="{{$propKind->id}}" @if(isset($filter['prop_kind']) && $filter['prop_kind'] == $propKind->id) selected="" @endif>{{$propKind->title}}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-basis-33">
            <label for="type-select">Тип</label>
            <select id="type-select" class="form-control" name="filter[type]">
                <option value="all">Все типы</option>
                @foreach($propTypes as $propType)
                    <option value="{{$propType->id}}" @if(isset($filter['type']) && $filter['type'] == $propType->id) selected="" @endif>{{$propType->title}}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-basis-33">
            <label for="insert-select">Для вложенных категорий</label>
            <select id="insert-select" class="form-control" name="filter[is_insert]">
                <option value="all">Все</option>
                <option value="1" @if(isset($filter['is_insert']) && $filter['is_insert'] == 1) selected="" @endif>Вложенные</option>
                <option value="0" @if(isset($filter['is_insert']) && $filter['is_insert'] == 0) selected="" @endif>Не вложенные</option>
            </select>
        </div>

    </div>

    <div class="flex">

        <div class="flex-basis-50">
            <label for="prop-group">Группа свойств</label>
            <select id="prop-group" class="form-control selectpicker" data-live-search="true" name="filter[group_id]">
                <option value="all">Все группы</option>
                <option value="NULL" @if(isset($filter['group_id']) && $filter['group_id'] == 'NULL') selected="" @endif>Без группы</option>
                @foreach($propGroups as $propGroup)
                    <option value="{{$propGroup->id}}" @if(isset($filter['group_id']) && $filter['group_id'] == $propGroup->id) selected="" @endif>{{$propGroup->title}}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-basis-50">
            <label for="category-select">Категория</label>
            <select id="category-select" class="form-control selectpicker" data-live-search="true" name="filter[category_id]">
                <option value="all">Все категории</option>
                <option value="NULL" @if(isset($filter['category_id']) && $filter['category_id'] == 'NULL') selected="" @endif>Для всех категорий</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @if(isset($filter['category_id']) && $filter['category_id'] == $category->id) selected="" @endif>{{$category->title}}</option>
                @endforeach
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