
<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$order->title or ""}}" required>

<label for="name">Имя покупателя</label>
<input type="text" id="name" class="form-control" name="name" value="{{$order->name or ""}}">

<label for="email">Email покупателя</label>
<input type="text" id="email" class="form-control" name="email" value="{{$order->email or ""}}">

<label for="phone">Телефон покупателя</label>
<input type="text" id="phone" class="form-control" name="phone" value="{{$order->phone or ""}}">

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

<input type="hidden" name="created_by"
   @if(isset($order->id))
       value="{{$order->created_by or ""}}"
   @else
       value="{{$user->id or ""}}"
    @endif
>

<div class="form-buttons">
<input class="btn btn-primary" type="submit" name="save" value="Сохранить">

@if(isset($order->id))
    <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
@endif
</div>