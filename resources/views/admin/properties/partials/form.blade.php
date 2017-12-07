
<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$property->title or ""}}" required>

<label for="slug">Символьный код</label>
<input type="text" id="slug" class="form-control" name="slug" value="{{$property->slug or ""}}">

<label for="prop_kind">Вид (к чему относится - категории или материалу)</label>
<select id="prop_kind" class="form-control" name="prop_kind">
    @foreach($propKinds as $propKind)
        <option value="{{$propKind->id}}" @if(isset($property->id) && $property->prop_kind == $propKind->id) selected="" @endif>{{$propKind->title}}</option>
    @endforeach
</select>

<label for="group_id">Группа свойств</label>
<select id="group_id" class="form-control selectpicker" data-live-search="true" name="group_id">
    <option value="0">Без группы</option>
    @foreach($propGroups as $propGroup)
        <option value="{{$propGroup->id}}" @if(isset($property->id) && $property->group_id == $propGroup->id) selected="" @endif>{{$propGroup->title}}</option>
    @endforeach
</select>

<label for="type">Тип свойства</label>
<select id="type" class="form-control selectpicker" data-live-search="true" name="type">
    @foreach($propTypes as $propType)
        <option value="{{$propType->id}}" @if(isset($property->id) && $property->type == $propType->id) selected="" @endif>{{$propType->title}}</option>
    @endforeach
</select>

<label for="category_id">Категория</label>
<select id="category_id" class="form-control selectpicker" data-live-search="true" name="category_id">
    <option value="0">Для всех категорий</option>
    @include('admin.properties.partials.categories', ['categories' => $categories])
</select>

<label for="is_insert">Принадлежность к вложенным категориям</label>
<select id="is_insert" class="form-control" name="is_insert">
    <option value="1" @if(isset($property->id) && $property->is_insert == 1) selected="" @endif>Да</option>
    <option value="0" @if(isset($property->id) && $property->is_insert == 0) selected="" @endif>Нет</option>
</select>

<label for="order">Порядок</label>
<input type="number" id="order" class="form-control" name="order" value="{{$property->order or ""}}">

@isset($property->type)
    @if($property->type == PROP_TYPE_LIST)
        <h4>Значения списка</h4>
        <div class="add-input-wrap">
            @if(isset($propEnums))
                @foreach($propEnums as $key=>$propEnum)
                    <input type="text" class="form-control" name="prop_enums[{{$propEnum['id']}}]" value="{{$propEnum['title'] or ""}}">
                @endforeach
            @endif
                <input type="text" class="form-control" name="prop_enums_add[]" value="">
                <button class="btn btn-primary" onclick="addInput('.add-input-wrap'); return false">Добавить</button>
        </div>
    @elseif($property->type == PROP_TYPE_CATEGORY_LINK || $property->type == PROP_TYPE_FILE || $property->type == PROP_TYPE_IMG)

    @else
        <label for="default">Значение по умолчанию</label>
        <input type="text" id="default" class="form-control" name="default" value="{{$property->default or ""}}">
    @endif
@endisset

<input type="hidden" name="id" value="{{$property->id or ""}}">
<input type="hidden" name="old_type" value="{{$property->type or ""}}">
<input type="hidden" name="old_category_id" value="{{$property->category_id or ""}}">

<div class="form-buttons" style="margin: 20px 0;">
    <input class="btn btn-primary" type="submit" name="save" value="Сохранить">

    @if(isset($property->id))
        <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
    @endif
</div>