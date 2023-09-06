<?php

namespace Wallex;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Wallex\Abstracts\WallexClient;

class Payout extends WallexClient
{
    public const CRYPTO = 'crypto';
    public const FIAT = 'fiat';


    /**
     * Получение списка выплат
     *
     * @return array - В ответе будет массив items содержащий: id* - ID выплаты status* - 0 - создана, 1 - ожидает модерации, 2 - отклонена, 3 - выполнена, 4 - отправка callback, 5 - в процессе выплаты data* - Данные переданные при создании выплаты
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getAll(): array
    {
        $respond = $this->client->post('/payout/list', [
            'json' => [
                'merchant' => $this->merchantId,
                'sign' => sha1($this->merchantId . $this->secretKey)
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }


    /**
     * Выплата в крипте
     *
     * @param string $address Адрес для перевода
     * @param float $amount Сумма средств для зачисления (в абсолютном значении, с плавающей точкой, пр.: 123.45)
     * @param string $currency Валюта оплаты
     * @return array - Result
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    public function cryptoPay(
        string $address,
        float  $amount,
        string $currency
    ): array
    {
        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'type' => self::CRYPTO,
            'address' => $address,
            'merchant' => $this->merchantId,
        ];
        $data['sign'] = $this->calculateSign($data);

        $respond = $this->client->post('/payout/new', [
            'json' => $data
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     * Выплата в фиате
     *
     * @param float $amount Сумма средств для зачисления (в абсолютном значении, с плавающей точкой, пр.: 123.45)
     * @param string $currency Валюта оплаты
     * @param string $bank Банк выплаты - Альфа кешин,Тинькофф,Сбер,Киви,Тинькофф кешин,Все банки РФ,KZT,EUR
     * @param string $number Номер банковской карты
     * @param int $month Месяц из срока действия карты
     * @param int $year Год из срока действия карты
     * @param string $fiat Валюта выплаты (RUB, EUR)
     * @param string $cardholder Имя держателя карты (заполняется для евро)
     * @param string $dateOfBirth Дата рождения держателя карты (заполняется для евро), например 1999-12-12
     *
     * @return array - Result
     * @throws GuzzleException
     * @throws JsonException
     */
    public function fiatPay(
        float  $amount,
        string $currency,
        string $bank,
        string $number,
        int    $month,
        int    $year,
        string $fiat,
        string $cardholder,
        string $dateOfBirth
    ): array
    {
        $data = [
            'merchant' => $this->merchantId,
            'amount' => $amount,
            'currency' => $currency,
            'bank' => $bank,
            'number' => $number,
            'month' => $month,
            'year' => $year,
            'type' => self::FIAT,
            'fiat' => $fiat,
            'cardholder' => $cardholder,
            'dateOfBirth' => $dateOfBirth
        ];

        $data['sign'] = $this->calculateSign($data);

        $respond = $this->client->post('/payout/new', [
            'json' => $data
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }


    /**
     * Перевести USDT на выплатной баланс
     *
     * @param float $amount Сумма для перевода на выплатной баланс
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function payToBalance(
        float $amount
    ): array
    {
        $respond = $this->client->post('/payout/to_pay_balance', [
            'json' => [
                'amount' => $amount,
                'merchant' => $this->merchantId,
                'sign' => $this->calculateSign([$this->merchantId, $amount])
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     *  Конвертация
     *
     * @param float $amount Сумма для конвертации
     * @param string $currency Валюта в которую нужно конвертировать (RUB, EUR)
     * @throws GuzzleException
     * @throws JsonException
     */
    public function convert(
        float  $amount,
        string $currency
    ): array
    {
        $respond = $this->client->post('/payout/to_pay_balance', [
            'json' => [
                'amount' => $amount,
                'merchant' => $this->merchantId,
                'currency' => $currency,
                'sign' => $this->calculateSign([$this->merchantId, $amount, $currency])
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }

    /**
     *  Получение баланса
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getBalance(): array
    {
        $respond = $this->client->post('/payout/balance', [
            'json' => [
                'merchant' => $this->merchantId,
                'sign' => $this->calculateSign([$this->merchantId])
            ]
        ]);

        return $this->toArray($respond->getBody()->getContents());
    }
}