<?php

namespace App\Http\Controllers\Site;

use App;
use App\Order;
use App\Delivery;
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

    public function showBasket(Request $request)
    {
        $arToBasket = [];
        $items = [];

        $template = TemplateController::getInstance();
        if($template->isInstance == 'N') $template->setTemplateVariables();

        $arToBasket = unserialize($request->cookie('basket'));

        if (!empty($arToBasket))
            $items = $this->getProductList($arToBasket);

        // Deliveries
        $deliveries = Delivery::orderby('order', 'asc')->select(['id', 'title', 'price'])->get();

        return view('public/basket/basket', [
            'items' => $items,
            'price' => $this->price,
            'title' =>  $this->title,
            'deliveries' => $deliveries,
            'template' => $template
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

        $arToBasket = unserialize($request->cookie('basket'));

        $arToBasket[$request->productId] = [
            'id' => $request->productId,
            'quantity' => $request->quantity
        ];

        $cookieJar->queue(cookie('basket', serialize($arToBasket), 1000000));
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

            $arToBasket = unserialize($request->cookie('basket'));
            unset($arToBasket[$request->productId]);
            $cookieJar->queue(cookie('basket', serialize($arToBasket), 1000000));
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
        $requestData['full_content'] = $request->cookie('basket');

        if ($requestData['delivery'] && $requestData['delivery'] > 0) {
            $delivery = Delivery::where('id', $requestData['delivery'])->select(['id', 'title', 'price'])->first();
            $requestData['price'] += $delivery->price;
        }

        $order = Order::create($requestData);

        $order->title = $order->id . '-' . $order->title;
        $order->update();

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
        $order = $orderId . '-' . $requestData['title'];
        $price = $requestData['price'];
        $deliveryId = $requestData['delivery'];
        $deliveryName = "Не задано";

        if ($deliveryId && $deliveryId > 0) {
            $delivery = Delivery::where('id', $deliveryId)->select(['id', 'title', 'price'])->first();
            $deliveryName = $delivery->title;
            $price += $delivery->price;
        }


        $headers = "Content-type: text/plain; charset = utf-8";
        $subject = "Новый заказ";
        $message = "Имя: $name \nТелефон: $phone \nЭлектронный адрес: $email \nНомер заказа: $orderId \nЗаказ: $order \nДоставка: $deliveryName \nЦена: $price";
        $send = mail ($mail, $subject, $message, $headers);
    }
}
