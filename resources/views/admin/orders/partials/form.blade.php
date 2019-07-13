
<label for="title">Заголовок</label>
<input type="text" id="title" class="form-control" name="title" value="{{$order->title ?? ""}}" required>

<label for="name">Имя покупателя</label>
<input type="text" id="name" class="form-control" name="name" value="{{$order->name ?? ""}}">

<label for="email">Email покупателя</label>
<input type="text" id="email" class="form-control" name="email" value="{{$order->email ?? ""}}">

<label for="phone">Телефон покупателя</label>
<input type="text" id="phone" class="form-control" name="phone" value="{{$order->phone ?? ""}}">

<label for="address">Адрес</label>
<input type="text" id="address" class="form-control" name="address" value="{{$order->address ?? ""}}">

<label for="price">Сумма заказа</label>
<input type="number" id="price" class="form-control" name="price" value="{{$order->price ?? ""}}">

<label for="status_order">Статус заказа</label>
<select id="status_order" class="form-control" name="status_order">
    @foreach($status_orders as $status_order)
        <option value="{{$status_order->id}}" @if(isset($order->status_order) && $order->status_order == $status_order->id) selected="" @endif>{{$status_order->title}}</option>
    @endforeach
</select>

<label for="delivery">Доставка</label>
<select id="delivery" class="form-control" name="delivery">
    @foreach($deliveries as $delivery)
        <option value="{{$delivery->id}}" @if(isset($order->delivery) && $order->delivery == $delivery->id) selected="" @endif data-delivery-price="{{$delivery->price}}">{{$delivery->title}} ({{$delivery->price}} руб.)</option>
    @endforeach
</select>

@if(!empty($productList))
<label>Состав заказа</label>
    <div class="basket-box">
        @foreach($productList as $key=>$item)
                <div class="basket-item flex" data-product-id="{{$item['id']}}">
                    <div class="basket-img flex">
                        @if (!empty($item['preview_img'][0]))
                            <a target="_blank" href="{{route('item.showProduct', ['category_slug' => $item['category']['slug'], 'item_slug' => $item['slug']])}}">
                                <img src="{{$item['preview_img'][0]['MIDDLE']}}" alt="{{$item['title']}}" title="{{$item['title']}}"/>
                            </a>
                        @endif
                    </div>
                    <div class="basket-title flex">
                        <a target="_blank" href="{{route('item.showProduct', ['category_slug' => $item['category']['slug'], 'item_slug' => $item['slug']])}}" >{{$item['title']}}</a>
                    </div>
                    <div class="basket-quantity flex">
                        <input class="form-control" type="number" name="quantity[{{$item['id']}}]" value="{{$item['quantity'] ?? '1'}}"/>
                    </div>
                    <div class="basket-price flex">
                        <span class="basket-price-value" data-product-price="{{$item['fullprice'] ?? '0'}}">{{$item['fullprice'] ?? '0'}}</span>&nbsp;руб.
                    </div>
                    <div class="basket-delete flex">
                        <input class="btn btn-danger" type="submit" name="deleteProduct[{{$item['id']}}]" value="Удалить">
                    </div>
                </div>
        @endforeach
    </div>
@endif

<label for="add_product">Добавление товара</label>
<select id="add_product" class="form-control selectpicker" data-live-search="true" name="add_product">
    <option value="0">Не добавлять</option>
    @foreach($products as $product)
        <option value="{{$product['id']}}">{{$product['title']}}</option>
    @endforeach
</select>


<input type="hidden" name="created_by"
   @if(isset($order->id))
       value="{{$order->created_by ?? ""}}"
   @else
       value="{{$user->id ?? ""}}"
    @endif
>

<div class="form-buttons">
<input class="btn btn-primary" type="submit" name="save" value="Сохранить">
    <input class="btn btn-primary" type="submit" name="changePrice" value="Пересчитать цену" onclick="event.preventDefault(); enterShopAdmin.changePrice()">

@if(isset($order->id))
    <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
@endif
</div>