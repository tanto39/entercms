<form method="post" action="{{route('admin.menuitem.filter')}}">

    <div class="flex">
        <div class="flex-basis-50">
            <label for="menu-select">Меню</label>
            {{csrf_field()}}
            <select id="menu-select" class="form-control selectpicker" data-live-search="true" name="filter[menu]">
                <option value="all">Все меню</option>
                @foreach($menus as $menu)
                    <option value="{{$menu->id}}" @if(isset($filter['menu']) && $filter['menu'] == $menu->id) selected="" @endif>{{$menu->title}}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-basis-50">
            <label for="type-select">Тип пункта меню</label>
            {{csrf_field()}}
            <select id="type-select" class="form-control selectpicker" data-live-search="true" name="filter[type]">
                <option value="all">Все типы</option>
                @foreach($menutypes as $menutype)
                    <option value="{{$menutype->id}}" @if(isset($filter['menutype']) && $filter['menutype'] == $menutype->id) selected="" @endif>{{$menutype->title}}</option>
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