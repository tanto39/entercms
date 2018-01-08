<?php

namespace App\Http\Controllers\Site;

use App;
use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Create order
     *
     * @param Request $request
     * @return string
     */
    public static function store(Request $request)
    {
        $requestData = $request->all();

        if ($requestData['capcha'] != 4) {
            return "Неверна введена сумма чисел. 2 + 2 = 4";
        }

        $order = Order::create($requestData);

        self::sendMailOrder(ADMIN_EMAIL, $requestData, $order->id);
        self::sendMailOrder($requestData['email'], $requestData, $order->id);

        if (isset($order->id))
            return "Спасибо за заказ. Наш менеджер свяжется с вами в ближайшее время для уточнения деталей. Номер заказа - " . $order->id;
        else
            return "Произошла ошибка. Повторите заказ.";
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

        if ($send == 'true')
        {
            echo "<p>Информация о заказе выслана на электронную почту!</p>";
        }
        else
        {
            echo "<p>Ошибка при доставке информации о заказе на электронную почту.</p>";
        }
    }
}
