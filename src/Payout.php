<?php

namespace Wallex;

use GuzzleHttp\Exception\GuzzleException;
use Wallex\Abstracts\WallexClient;

class Payout
{
    public const CRYPTO = 'crypto';
    public const FIAT = 'fiat';

    protected WallexClient $wallex;
    protected int $merchantId;
    protected string $secretKey;

    /**
     * @param int $merchantId Идентификатор магазина
     * @param string $secretKey Ваш SECRET KEY
     * @see https://wallex.online/api_for_payments#instr-api-pop
     */
    public function __construct(int $merchantId, string $secretKey)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->wallex = new WallexClient();
    }

    /**
     * Получение списка выплат
     *
     * @return array - В ответе будет массив items содержащий: id* - ID выплаты status* - 0 - создана, 1 - ожидает модерации, 2 - отклонена, 3 - выполнена, 4 - отправка callback, 5 - в процессе выплаты data* - Данные переданные при создании выплаты
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getAll(): array
    {
        $respond = $this->wallex->client->post('/payout/list', [
            'json' => [
                'merchant' => $this->merchantId,
                'sign' => sha1($this->merchantId . $this->secretKey)
            ]
        ]);

        return json_decode($respond->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
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
     * @throws \JsonException
     */
    public function cryptoPay(
        string $address,
        float  $amount,
        string $currency
    ): array
    {
        $data = [
            'merchant' => $this->merchantId,
            'amount' => $amount,
            'currency' => $currency,
            'address' => $address,
            'type' => self::CRYPTO
        ];
        $data['sign'] = sha1(implode('', $data) . $this->secretKey);

        $respond = $this->wallex->client->post('/payout/new', [
            'json' => $data
        ]);

        return json_decode($respond->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
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
     * @param string $fiat  Валюта выплаты (RUB, EUR)
     * @param string $cardholder Имя держателя карты (заполняется для евро)
     * @param string $dateOfBirth Дата рождения держателя карты (заполняется для евро), например 1999-12-12
     *
     * @return array - Result
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function fiatPay(
        float  $amount,
        string $currency,
        string $bank,
        string $number,
        int $month,
        int $year,
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
        $data['sign'] = sha1(implode('', $data) . $this->secretKey);

        $respond = $this->wallex->client->post('/payout/new', [
            'json' => $data
        ]);

        return json_decode($respond->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }


    /**
     * Перевести USDT на выплатной баланс
     *
     * @param float $amount Сумма для перевода на выплатной баланс
     * @return array
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function payToBalance(
        float $amount
    ): array
    {
        $respond = $this->wallex->client->post('/payout/to_pay_balance', [
            'json' => [
                'amount' => $amount,
                'merchant' => $this->merchantId,
                'sign' => sha1($this->merchantId . $amount . $this->secretKey)
            ]
        ]);

        return json_decode($respond->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     *  Конвертация 
     *
     * @param float $amount Сумма для конвертации
     * @param string $currency Валюта в которую нужно конвертировать (RUB, EUR)
     * @return mixed
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function convert(
        float  $amount,
        string $currency
    )
    {
        $respond = $this->wallex->client->post('/payout/to_pay_balance', [
            'json' => [
                'amount' => $amount,
                'merchant' => $this->merchantId,
                'currency' => $currency,
                'sign' => sha1($this->merchantId . $amount . $currency . $this->secretKey)
            ]
        ]);

        return json_decode($respond->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

}