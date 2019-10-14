
<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$region->title ?? ""}}" required>

<label for="slug">Символьный код</label>
<input type="text" id="slug" class="form-control" name="slug" value="{{$region->slug ?? ""}}">

<label for="regwhere">Предложный падеж</label>
<input type="text" id="regwhere" class="form-control" name="regwhere" value="{{$region->regwhere ?? ""}}">

<label for="order">Порядок</label>
<input type="number" id="order" class="form-control" name="order" value="{{$region->order ?? ""}}">

<label for="phone">Телефон</label>
<input type="text" id="phone" class="form-control" name="phone" value="{{$region->phone ?? ""}}">

<label for="address">Адрес</label>
<input type="text" id="address" class="form-control" name="address" value="{{$region->address ?? ""}}">

<label for="mail">Эл. почта</label>
<input type="text" id="mail" class="form-control" name="mail" value="{{$region->mail ?? ""}}">

<label for="regmap">Карта</label>
<textarea id="regmap" class="form-control" name="regmap" rows="3">{{$region->regmap ?? ""}}</textarea>

<label for="regtext">Текст</label>
<textarea id="regtext" class="form-control" name="regtext" rows="3">{{$region->regtext ?? ""}}</textarea>

<div class="form-buttons">
<input class="btn btn-primary" type="submit" name="save" value="Сохранить">

@if(isset($region->id))
    <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
@endif
</div>