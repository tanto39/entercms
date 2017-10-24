
<label for="published">Статус</label>
<select id="published" class="form-control" name="published">
    @if(isset($category->id))
        <option value="0" @if($category->published == 0) selected="" @endif>Не опубликовано</option>
        <option value="1" @if($category->published == 1) selected="" @endif>Опубликовано</option>
    @else
        <option value="0">Не опубликовано</option>
        <option value="1">Опубликовано</option>
    @endif
</select>

<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$category->title or ""}}" required>

<label for="slug">Ссылка</label>
<input type="text" id="slug" class="form-control" name="slug" value="{{$category->slug or ""}}">

<label for="parent_id">Родительская категория</label>
<select id="parent_id" class="form-control" name="parent_id">
    <option value="0">-- Без родителя</option>
    @include('admin.categories.partials.categories', ['categories' => $categories])
</select>

<label for="meta_key">Мета тег keywords</label>
<input type="text" id="meta_key" class="form-control" name="meta_key" value="{{$category->meta_key or ""}}">

<label for="meta_desc">Мета тег description</label>
<input type="text" id="meta_desc" class="form-control" name="meta_desc" value="{{$category->meta_desc or ""}}">

<label for="description">Описание</label>
<textarea id="description" class="form-control" name="description" rows="3">
    {{$category->description or ""}}
</textarea>

<label for="content">Контент</label>
<textarea id="content" class="form-control" name="content" rows="15">
    {{$category->content or ""}}
</textarea>

<hr>

<input class="btn btn-primary" type="submit" value="Сохранить">