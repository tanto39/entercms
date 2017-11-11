
<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$propgroup->title or ""}}" required>

<label for="slug">Символьный код</label>
<input type="text" id="slug" class="form-control" name="slug" value="{{$propgroup->slug or ""}}">

<label for="order">Порядок</label>
<input type="number" id="order" class="form-control" name="order" value="{{$propgroup->order or ""}}">

</br>
<input class="btn btn-primary" type="submit" name="save" value="Сохранить">

@if(isset($propgroup->id))
    <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
@endif