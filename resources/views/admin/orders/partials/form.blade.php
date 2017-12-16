
<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$order->title or ""}}" required>

<label for="price">Сумма заказа</label>
<input type="number" id="price" class="form-control" name="price" value="{{$order->price or ""}}">

<label for="status_order">Статус заказа</label>
<select id="status_order" class="form-control" name="status_order">
    @foreach($status_orders as $status_order)
        <option value="{{$status_order->id}}" @if(isset($order->status_order) && $order->status_order == $status_order->id) selected="" @endif>{{$status_order->title}}</option>
    @endforeach
</select>

<label for="full_content">Состав заказа</label>
<textarea id="full_content" class="form-control" name="full_content" rows="15">
    {{$order->full_content or ""}}
</textarea>

<div class="form-buttons">
<input class="btn btn-primary" type="submit" name="save" value="Сохранить">

@if(isset($order->id))
    <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
@endif
</div>