<?php

namespace Wallex\Abstracts;


use GuzzleHttp\Client;

class WallexClient
{
    public const BASE_URL = 'https://wallex.online';

    public Client $client;

    public function __construct(string $apiKey = null)
    {
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
}