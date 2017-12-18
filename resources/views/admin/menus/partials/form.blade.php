
<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$menu->title or ""}}" required>

<label for="slug">Символьный код</label>
<input type="text" id="slug" class="form-control" name="slug" value="{{$menu->slug or ""}}">

<label for="order">Порядок</label>
<input type="number" id="order" class="form-control" name="order" value="{{$menu->order or ""}}">

<div class="form-buttons">
<input class="btn btn-primary" type="submit" name="save" value="Сохранить">

@if(isset($menu->id))
    <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
@endif
</div>