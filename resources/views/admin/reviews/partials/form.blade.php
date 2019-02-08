
<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$review->title ?? ""}}" required>

<label for="published">Статус</label>
<select id="published" class="form-control" name="published">
    <option value="1" @if(isset($review->id) && $review->published == 1) selected="" @endif>Опубликовано</option>
    <option value="0" @if(isset($review->id) && $review->published == 0) selected="" @endif>Не опубликовано</option>
</select>

<label for="author_name">Имя автора</label>
<input type="text" id="author_name" class="form-control" name="author_name" value="{{$review->author_name ?? ""}}" required>

<label for="slug">Символьный код</label>
<input type="text" id="slug" class="form-control" name="slug" value="{{$review->slug ?? ""}}">

<label for="order">Порядок</label>
<input type="number" id="order" class="form-control" name="order" value="{{$review->order ?? ""}}">

<label for="rating">Рейтинг</label>
<input type="number" id="rating" class="form-control" name="rating" value="{{$review->rating ?? ""}}">

<label for="item_id">Материал</label>
<select id="item_id" class="form-control selectpicker" data-live-search="true" name="item_id">
    @foreach($items as $item)
        <option value="{{$item->id}}" @if(isset($review->item_id) && $review->item_id == $item->id) selected="" @endif>{{$item->title}}</option>
    @endforeach
</select>

<label for="parent_id">Родительский отзыв</label>
<select id="parent_id" class="form-control selectpicker" data-live-search="true" name="parent_id">
    <option value="0">Без родителя</option>
    @foreach($reviews as $itemReview)
        <option value="{{$itemReview->id}}" @if(isset($review->parent_id) && $review->parent_id == $itemReview->id) selected="" @endif>{{$itemReview->title}}</option>
    @endforeach
</select>

<label for="content">Контент</label>
<textarea id="content" class="form-control" name="full_content" rows="15">{{$review->full_content ?? ""}}</textarea>

<div class="form-buttons">
<input class="btn btn-primary" type="submit" name="save" value="Сохранить">

@if(isset($review->id))
    <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
@endif
</div>