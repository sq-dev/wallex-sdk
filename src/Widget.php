<?php

namespace Wallex;

use Exception;

class Widget
{
    protected int $merchantId;
    protected string $secretKey;

    protected const BASE_URL = 'https://wallex.online/widget/%d?data=%s';

    public function __construct(int $merchantId, string $secretKey)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
    }

    /**
     * Создание платежа
     *
     * @param string $client email адрес клиента
     * @param string $product Назначение платежа или, наименование продукта
     * @param float $price Сумма за одну единицу товара
     * @param int $quantity Количество, если больше 1 то итоговая сумма будет quantity*price
     * @param string $message Краткое сообщение пользователю будет отправлено на email при оплате
     * @param string $description Краткое описание услуги, будет отображаться в платежной форме
     * @param string $currency Код криптовалюты для оплаты (например usdt)
     * @param string $fiat_currency Код фиатной валюты (rub, uah, kzt, try) для оплаты, по умолчанию - rub
     * @param string $language Локаль транзакции, по умолчанию - ru
     * @param string|null $uuid Уникальный номер платежа в вашей системе. Если Вы не используете идентификаторы - просто передайте в этом параметре рандомное значение
     * @return string - URL для оплаты
     * @throws Exception
     */
    public function cretePayment(
        string  $client,
        string  $product,
        float   $price,
        int     $quantity,
        string  $message,
        string  $description,
        string  $currency = 'USDT',
        string  $fiat_currency = 'rub',
        string  $language = 'ru',
        ?string $uuid = null
    ): string
    {
        $data = [
            'client' => $client,
            'product' => $product,
            'price' => $price * 100,
            'quantity' => $quantity,
            'message' => $message,
            'description' => $description,
            'currency' => $currency,
            'fiat_currency' => $fiat_currency,
            'language' => $language,
            'uuid' => is_null($uuid) ? random_int(10000, 99999) : $uuid,
        ];

        $data['sign'] = sha1(implode('', $data) . $this->secretKey);
        $data = http_build_query($data);

        return sprintf(self::BASE_URL, $this->merchantId, base64_encode($data));
    }
}