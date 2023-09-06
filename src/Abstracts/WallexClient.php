<?php

namespace Wallex\Abstracts;


use GuzzleHttp\Client;

class WallexClient
{
    public const BASE_URL = 'https://wallex.online';

    public Client $client;

    protected int $merchantId;

    protected string $secretKey;

    /**
     * @param int $merchantId Идентификатор магазина
     * @param string $secretKey Ваш SECRET KEY
     * @param string|null $apiKey Ваш API KEY
     * @see https://wallex.online/api_for_payments#instr-api-pop
     */
    public function __construct(int $merchantId, string $secretKey, string $apiKey = null)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;

        $config = [
            'base_uri' => self::BASE_URL,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ];

        if ($apiKey){
            $config['headers']['X-Api-Key'] = $apiKey;
        }

        $this->client = new Client($config);
    }

    /**
     * @param array $data
     * @return string
     */
    public function calculateSign(array $data): string
    {
        return sha1(implode('', $data) . $this->secretKey);
    }

    /**
     * @param string $json
     * @return array
     * @throws \JsonException
     */
    protected function toArray(string $json): array
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}