<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$delivery->title ?? ""}}" required>

<label for="published">Статус</label>
<select id="published" class="form-control" name="published">
    <option value="1" @if(isset($delivery->id) && $delivery->published == 1) selected="" @endif>Опубликовано</option>
    <option value="0" @if(isset($delivery->id) && $delivery->published == 0) selected="" @endif>Не опубликовано</option>
</select>

<label for="price">Цена</label>
<input type="text" id="price" class="form-control" name="price" value="{{$delivery->price ?? ""}}">

<label for="order">Порядок</label>
<input type="number" id="order" class="form-control" name="order" value="{{$delivery->order ?? ""}}">

<label for="full_content">Описание доставки</label>
<textarea id="full_content" class="form-control" name="full_content" rows="15">
    {{$delivery->full_content ?? ""}}
</textarea>

<hr>

<input type="hidden" name="created_by"
    @if(isset($delivery->id))
         value="{{$delivery->created_by ?? ""}}"
    @else
        value="{{$user->id ?? ""}}"
    @endif
>

<input type="hidden" name="modify_by" value="{{$user->id ?? ""}}">

<div class="form-buttons">
    <input class="btn btn-primary" type="submit" name="save" value="Сохранить">

    @if(isset($delivery->id))
    <div class="form-buttons">
        <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
    </div>
    @endif
</div>