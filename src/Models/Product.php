<?php

namespace Wallex\Models;

class Product extends WallexModel
{
    /**
     * @var string email адрес клиента
     */
    private string $client;

    /**
     * @var string Назначение платежа или, наименование продукта
     */
    private string $product;

    /**
     * @var float Сумма за одну единицу товара
     */
    private float $price;

    /**
     * @var int Количество, если больше 1 то итоговая сумма будет quantity*price
     */
    private int $quantity;

    /**
     * @var string Краткое сообщение пользователю будет отправлено на email при оплате
     */
    private string $message;

    /**
     * @var string Краткое описание услуги, будет отображаться в платежной форме
     */
    private string $description;

    /**
     * @var string Код криптовалюты для оплаты (например usdt)
     */
    private string $currency;

    /**
     * @var string Код фиатной валюты (rub, uah, kzt, try) для оплаты, по умолчанию - rub
     */
    private string $fiat_currency;

    /**
     * @var string Локаль транзакции, по умолчанию - ru
     */
    private string $language;

    /**
     * @var string|int|null Уникальный номер платежа в вашей системе. Если Вы не используете идентификаторы - просто передайте в этом параметре рандомное значение
     */
    private ?string $uuid;

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
     */

    public function __construct(
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
    )
    {
        $this->client = $client;
        $this->product = $product;
        $this->price = $price * 100;
        $this->quantity = $quantity;
        $this->message = $message;
        $this->description = $description;
        $this->currency = $currency;
        $this->fiat_currency = $fiat_currency;
        $this->language = $language;
        $this->uuid = is_null($uuid) ? random_int(10000, 99999) : $uuid;
    }

    public function getFiatCurrency(): string
    {
        return $this->fiat_currency;
    }

    public function setFiatCurrency(string $fiat_currency): void
    {
        $this->fiat_currency = $fiat_currency;
    }

    public function getClient(): string
    {
        return $this->client;
    }

    public function setClient(string $client): void
    {
        $this->client = $client;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function setProduct(string $product): void
    {
        $this->product = $product;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function toArray(): array
    {
        return [
            'client' => $this->client,
            'product' => $this->product,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'message' => $this->message,
            'description' => $this->description,
            'currency' => $this->currency,
            'fiat_currency' => $this->fiat_currency,
            'language' => $this->language,
            'uuid' => $this->uuid
        ];
    }
}