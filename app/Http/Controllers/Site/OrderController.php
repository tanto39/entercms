<?php

namespace App\Http\Controllers\Site;

use App;
use App\Order;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Cookie\CookieJar;

class OrderController extends Controller
{
    use \App\ImgController;
    use \App\PropEnumController;
    use \App\HandlePropertyController;
    use \App\CategoryTrait;
    use \App\OrderTrait;

    public function showBasket(CookieJar $cookieJar, Request $request)
    {
        $arToBasket = [];
        $items = [];

        $arToBasket = $request->cookie('basket');

        if (!empty($arToBasket))
            $items = $this->getProductList($arToBasket);

        return view('public/basket/basket', [
            'items' => $items,
            'price' => $this->price,
            'title' =>  $this->title
        ]);
    }

    /**
     * Add product to basket
     *
     * @param CookieJar $cookieJar
     * @param Request $request
     */
    public function addToBasket(CookieJar $cookieJar, Request $request)
    {
        $arToBasket = [];

        $arToBasket = $request->cookie('basket');

        $arToBasket[$request->productId] = [
            'id' => $request->productId,
            'quantity' => $request->quantity
        ];

        $cookieJar->queue(cookie('basket', $arToBasket, 1000000));
    }

    /**
     * Delete product from basket cookie
     *
     * @param CookieJar $cookieJar
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBasketItem(CookieJar $cookieJar, Request $request)
    {
        if ($request->delete) {
            $arToBasket = [];

            $arToBasket = $request->cookie('basket');
            unset($arToBasket[$request->productId]);
            $cookieJar->queue(cookie('basket', $arToBasket, 1000000));
        }

        return redirect()->route('item.basket');
    }

    /**
     * Create order
     *
     * @param CookieJar $cookieJar
     * @param Request $request
     * @return string
     */
    public static function store(CookieJar $cookieJar, Request $request)
    {
        $requestData = $request->all();

        if ($requestData['capcha'] != 4) {
            return "Неверна введена сумма чисел. 2 + 2 = 4";
        }

        // Order create
        $requestData['full_content'] = serialize($request->cookie('basket'));
        $order = Order::create($requestData);

        self::sendMailOrder(ADMIN_EMAIL, $requestData, $order->id);
        self::sendMailOrder($requestData['email'], $requestData, $order->id);

        if (isset($order->id))
            $request->session()->flash('success', "Спасибо за заказ. Наш менеджер свяжется с вами в ближайшее время для уточнения деталей. Номер заказа - " . $order->id);
        else
            $request->session()->flash("Произошла ошибка. Повторите заказ.");

        $cookieJar->queue($cookieJar->forget('basket'));

        return redirect()->route('item.basket');
    }

    /**
     * Send order info to email
     *
     * @param $mail
     * @param $requestData
     * @param $orderId
     */
    public static function sendMailOrder($mail, $requestData, $orderId)
    {
        $name = $requestData['name'];
        $phone = $requestData['phone'];
        $email = $requestData['email'];
        $order = $requestData['title'];
        $price = $requestData['price'];

        $headers = "Content-type: text/plain; charset = utf-8";
        $subject = "Новый заказ";
        $message = "Имя: $name \nТелефон: $phone \nЭлектронный адрес: $email \nТовар: $order \nЦена: $price";
        $send = mail ($mail, $subject, $message, $headers);
    }
}