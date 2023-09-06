<?php

namespace Wallex;

use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Wallex\Abstracts\WallexClient;
use Wallex\Models\Product;

class Exchange extends WallexClient
{

    /**
     * Создание заявки на оплату
     *
     * @param Product $product Объект продукта
     * @param string|null $cardNumber Номер карты получателя платежа (Используется только для эквайринга), поле не обязательное
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function create(Product $product, string $cardNumber = null): array
    {
        $data = $product->toArray();

        if ($cardNumber) {
            $data['card_number'] = $cardNumber;
        }

        $data['sign'] = $this->calculateSign($data);

        $respond = $this->client->post('exchange/create' . $this->merchantId, [
            'form_params' => $data
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Создание заявки на оплату
     *
     * При использовании метода createDeal объединяются шаги 1-4
     * @param Product $product Объект продукта
     * @param int $paymentMethod ID способа оплаты @see PaymentMethod
     * @param string|null $cardNumber Номер карты получателя платежа (Используется только для эквайринга), поле не обязательное
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     *
     * @see https://wallex.online/payment-api#/exchange/post_exchange_create_deal__id_
     */
    public function createDeal(Product $product, int $paymentMethod, string $cardNumber = null): array
    {
        $data = $product->toArray();
        $data['payment_method_id'] = $paymentMethod;
        $data['sign'] = sha1(implode('', $data) . $this->secretKey);

        if ($cardNumber) {
            $data['card_number'] = $cardNumber;
        }

        $respond = $this->client->post('exchange/create/' . $this->merchantId, [
            'form_params' => $data
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Получение списка заявок на оплату
     *
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getOffers(): array
    {
        $respond = $this->client->get('exchange/offers', [
            'query' => [
                'id' => $this->merchantId
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Старт покупки крипты за фиат по офферу из метода getOffers
     *
     * @param int $id ID заявки на оплату
     * @param int $buyId ID оффера
     * @param string $service Поле service из оффера
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function buy(
        int    $id,
        int    $buyId,
        string $service
    ): array
    {
        $respond = $this->client->post('exchange/buy', [
            'form_params' => [
                'id' => $id,
                'buyId' => $buyId,
                'service' => $service
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Подтверждение оплаты в фиате
     *
     * @param int $id ID заявки на оплату
     * @param int $buyId ID оффера
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function confirm(
        int $id,
        int $buyId
    ): array
    {
        $respond = $this->client->post('exchange/buy', [
            'form_params' => [
                'id' => $id,
                'buyId' => $buyId,
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Отмена заявки на оплату
     *
     * @param int $id ID заявки на оплату
     * @param int $buyId ID оффера
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function cancel(
        int $id,
        int $buyId
    ): array
    {
        $respond = $this->client->post('exchange/cancel', [
            'form_params' => [
                'id' => $id,
                'buyId' => $buyId,
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }


    /**
     * Эквайринг, отправка данных карты покупателя
     *
     * @param int $id ID заявки на оплату
     * @param int $buyId ID оффера
     * @param string $service Поле service из оффера
     * @param string $cardNumber Номер карты покупателя
     * @param string $expires Срок действия карты покупателя
     * @param string $cvc CVC код карты покупателя
     * @param string $email Email покупателя
     * @param string|null $cardTo Номер карты получателя платежа (Используется только для эквайринга), поле не обязательное
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function buyFiat(
        int    $id,
        int    $buyId,
        string $service,
        string $cardNumber,
        string $expires,
        string $cvc,
        string $email,
        string $cardTo = null
    ): array
    {
        $respond = $this->client->post('exchange/buy', [
            'form_params' => [
                'id' => $id,
                'buyId' => $buyId,
                'service' => $service,
                'cardNumber' => $cardNumber,
                'expires' => $expires,
                'cvc' => $cvc,
                'checkEmail' => $email,
                'cardTo' => $cardTo
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }


    /**
     * Получение информации по эквайрингу
     *
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getAcquiring(): array
    {
        $respond = $this->client->get('exchange/offers', [
            'query' => [
                'id' => $this->merchantId
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Получение информации по P2P
     *
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getP2PInfo(): array
    {
        $respond = $this->client->get('exchange/get', [
            'query' => [
                'id' => $this->merchantId
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Получение реквизитов для оплаты в фиате
     *
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getPaymentCredentials(): array
    {
        $respond = $this->client->get('exchange/get_payment_credentials', [
            'query' => [
                'id' => $this->merchantId
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Получение адреса для оплаты в крипте
     *
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getCryptoAddress(): array
    {
        $respond = $this->client->get('exchange/address', [
            'query' => [
                'id' => $this->merchantId
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Получение истории платежей
     *
     * @param string $wallet Валюта - rub, eur, usdt
     * @param DateTime $from Дата начала фильтра
     * @param DateTime $to Дата конца фильтра
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getHistory(string $wallet, DateTime $from, DateTime $to): array
    {
        $respond = $this->client->get('exchange/address', [
            'query' => [
                'wallet' => $wallet,
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d')
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

}