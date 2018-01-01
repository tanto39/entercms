<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$item->title or ""}}" required>

<label for="published">Статус</label>
<select id="published" class="form-control" name="published">
    <option value="1" @if(isset($item->id) && $item->published == 1) selected="" @endif>Опубликовано</option>
    <option value="0" @if(isset($item->id) && $item->published == 0) selected="" @endif>Не опубликовано</option>
</select>

<label for="is_product">Товар</label>
<select id="is_product" class="form-control" name="is_product">
    <option value="0" @if(isset($item->id) && $item->is_product == 0) selected="" @endif>Не товар</option>
    <option value="1" @if(isset($item->id) && $item->is_product == 1) selected="" @endif>Товар</option>
</select>

<label for="slug">Ссылка</label>
<input type="text" id="slug" class="form-control" name="slug" value="{{$item->slug or ""}}">

<label for="order">Порядок</label>
<input type="number" id="order" class="form-control" name="order" value="{{$item->order or ""}}">

<label for="rating">Рейтинг</label>
<input type="number" id="rating" class="form-control" name="rating" value="{{$item->rating or ""}}">

<div class="image-wrap">
<label for="preview_img">Изображение</label>
<input type="file" multiple id="preview_img" class="form-control" name="preview_img[]">
    <div id="image-placeholder" class="flex">
        @if(!empty($preview_images))
            @foreach($preview_images as $image)
                <div class="img-item">
                    <img src="{{ url('/images/shares/previews/'.$image['MIDDLE']) }}" alt="">
                    <button class="btn btn-danger" name="deleteImg" value="{{$image['MIDDLE']}}">Удалить</button>
                </div>
            @endforeach
        @endif
    </div>
</div>

<label for="category_id">Категория</label>
<select id="category_id" class="form-control selectpicker" data-live-search="true" name="category_id">
    <option value="0">-- Без категории</option>
    @include('admin.items.partials.categories', ['categories' => $categories])
</select>

<label for="meta_key">Мета тег keywords</label>
<input type="text" id="meta_key" class="form-control" name="meta_key" value="{{$item->meta_key or ""}}">

<label for="meta_desc">Мета тег description</label>
<input type="text" id="meta_desc" class="form-control" name="meta_desc" value="{{$item->meta_desc or ""}}">

<label for="description">Описание</label>
<textarea id="description" class="form-control" name="description" rows="3">{{$item->description or ""}}</textarea>

<label for="full_content">Контент</label>
<textarea id="full_content" class="form-control" name="full_content" rows="15">
    {{$item->full_content or ""}}
</textarea>

<hr>

<input type="hidden" name="created_by"
    @if(isset($item->id))
         value="{{$item->created_by or ""}}"
    @else
        value="{{$user->id or ""}}"
    @endif
>

{{-- Properties filter include --}}
@include("admin/partials.properties")

<input type="hidden" name="modify_by" value="{{$user->id or ""}}">

<div class="form-buttons">
    <input class="btn btn-primary" type="submit" name="save" value="Сохранить">

    @if(isset($item->id))
    <div class="form-buttons">
        <input class="btn btn-danger" type="submit" name="copy" value="Копировать">
        <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
    </div>
    @endif
</div>