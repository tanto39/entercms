@extends('public/layouts/app')

@section('content')
    <div class="container main">
        <div class="item-page">
            <h1>Корзина</h1>
            <div class="basket-wrap">

                @if(!empty($items))
                    <div class="basket-box">
                        @foreach($items as $key=>$item)
                            <form method="post" action="{{route('item.deletebasketitem')}}">
                                {{csrf_field()}}
                                <div class="basket-item flex" data-product-id="{{$item['id']}}">
                                    <input type="hidden" name="productId" value="{{$item['id']}}"/>
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
                                        <input class="form-control" type="number" value="{{$item['quantity'] ?? '1'}}" onchange="enterShop.setQuantityBasket({{$item['id']}}, $(this).val())"/>
                                    </div>
                                    <div class="basket-price flex">
                                        <span class="basket-price-value">{{$item['fullprice'] ?? '0'}}</span>&nbsp;руб.
                                    </div>
                                    <div class="basket-delete flex">
                                        <input class="btn btn-danger" type="submit" name="delete" value="Удалить">
                                    </div>
                                </div>
                            </form>
                        @endforeach
                        <div class="basket-summ">Общая сумма: <span class="sum-price">{{$price ?? "0"}}</span> руб.</div>
                    </div>

                    <div class="form-zakaz">
                        <form method="post" action="/sendorder">
                            {{csrf_field()}}
                            <div class="form-zakaz">
                                <input class="form-name form-control" type="text" placeholder="Введите имя" required name="name" size="16" />
                                <input class="form-phone form-control" type="tel" placeholder="8**********" required pattern="(\+?\d[- .]*){7,13}" title="Международный, государственный или местный телефонный номер" name="phone" size="16" />
                                <input class="form-mail form-control" type="email" placeholder="email@email.ru" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" name="email" size="16" />
                                <input class="form-address form-control" type="text" placeholder="Введите адрес, если нужна доставка (с индексом)" name="address" size="16" />

                                <label for="form-delivery">Доставка</label>
                                <select id="form-delivery" class="form-control" name="delivery" onchange="enterShop.setDelivery($('#form-delivery option:selected').data('delivery-price'), {{$price ?? "0"}})">
                                    @foreach($deliveries as $delivery)
                                        <option value="{{$delivery->id}}" data-delivery-price="{{$delivery->price}}">{{$delivery->title}} ({{$delivery->price}} руб.)</option>
                                    @endforeach
                                </select>

                                <input type="hidden" name="title" value="{{$title}}"/>
                                <input type="hidden" name="price" value="{{$price ?? '0'}}"/>
                                <div class="form-input form-pd"><label>Даю согласие на обработку <a href="#" target="_blank" rel="noopener noreferrer">персональных данных</a>:</label><input class="checkbox-inline" type="checkbox" required="" name="pd" /></div>
                                <label>Защита от спама: введите сумму 2+2:</label><input class="form-control form-capcha" type="number" required name="capcha"/>
                                <input class="btn form-submit order-button" type="submit" name="submit" value="Сделать заказ" />
                            </div>
                        </form>
                    </div>

                    <div class='message-form alert alert-success'><p>Загрузка...</p></div>
                @else
                    <div class="alert alert-success">Корзина пуста.</div>
                @endif
            </div>
        </div>
    </div>

@endsection
