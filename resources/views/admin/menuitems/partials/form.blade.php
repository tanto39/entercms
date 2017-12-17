<label for="title">Название</label>
<input type="text" id="title" class="form-control" name="title" value="{{$menuitem->title or ""}}" required>

<label for="slug">Символьный код</label>
<input type="text" id="slug" class="form-control" name="slug" value="{{$menuitem->slug or ""}}">

<label for="order">Порядок</label>
<input type="number" id="order" class="form-control" name="order" value="{{$menuitem->order or ""}}">

<label for="show_child">Показывать вложенные пункты</label>
<select id="show_child" class="form-control" name="show_child">
    <option value="1" @if(isset($menuitem->id) && $menuitem->show_child == 1) selected="" @endif>Да</option>
    <option value="0" @if(isset($menuitem->id) && $menuitem->show_child == 0) selected="" @endif>Нет</option>
</select>

<label for="menu-select">Меню</label>
{{csrf_field()}}
<select id="menu-select" class="form-control selectpicker" data-live-search="true" name="menu">
    @foreach($menus as $menu)
        <option value="{{$menu->id}}" @if(isset($menuitem->menu) && ($menuitem['menu'] == $menu->id)) selected="" @endif>{{$menu->title}}</option>
    @endforeach
</select>

<label for="type-select">Тип пункта меню</label>
{{csrf_field()}}
<select id="type-select" class="form-control selectpicker" data-live-search="true" name="type">
    @foreach($menutypes as $menutype)
        <option value="{{$menutype->id}}" @if(isset($menuitem->type) && ($menuitem['type'] == $menutype->id)) selected="" @endif>{{$menutype->title}}</option>
    @endforeach
</select>

@if(isset($menuitem->id) && ($menuitem['type'] == MENU_TYPE_CATEGORY || $menuitem['type'] == MENU_TYPE_ITEM))
    <label for="link_id">Привязка</label>
    {{csrf_field()}}
    <select id="link_id" class="form-control selectpicker" data-live-search="true" name="link_id">
        @isset($linkItems))
            @foreach($linkItems as $linkItem)
                <option value="{{$linkItem->id}}" @if($menuitem['link_id'] == $linkItem->id) selected="" @endif>{{$linkItem->title}}</option>
            @endforeach
        @endisset
    </select>
@endif

<label for="href">Ссылка</label>
<input type="text" id="href" class="form-control" name="href" value="{{$menuitem->href or ""}}">

<hr>

<div class="form-buttons">
    <input class="btn btn-primary" type="submit" name="save" value="Сохранить">

    @if(isset($menuitem->id))
        <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
    @endif
</div>