<?php

namespace Wallex;

class Webhook
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->data['uuid'];
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->data['amount'];
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->data['currency'];
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->data['status'];
    }

    /**
     * @return float
     */
    public function getCommission(): float
    {
        return $this->data['commission'];
    }

    /**
     * @return string
     */
    public function getProduct(): string
    {
        return $this->data['product'];
    }

    /**
     * @return string
     */
    public function getClient(): string
    {
        return $this->data['client'];
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->data['status'] === 'success';
    }

    /**
     * @param string $secretKey - секретный ключ мерчанта
     * @return bool
     */
    public function verifyPayment(string $secretKey): bool
    {
        $data = $this->data;
        unset($data['sign']);
        $signature = sha1(implode('', $data) . $secretKey);
        return $signature === $this->data['sign'];
    }
}