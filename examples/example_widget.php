<?php

use Wallex\Exchange;
use Wallex\Models\Product;
use Wallex\Widget;

include __DIR__.'/../vendor/autoload.php';

$widget = new Exchange(220, '744d8cd1-d444-4fa1-a6c9-58fe91ee6bef', 'ca92f945ba65efe9159ec14fbdaf8760');
$product = new Product(
    'client@mail.ru',
    'Xiaomi 9T',
    3,
    3,
    'Hello thanks for order',
    'Xiaomi 9T',
    'usdt',
    'usd',
    'en'
);

try {
    $url = $widget->getCryptoAddress();
    print_r($url);
}catch (\GuzzleHttp\Exception\ClientException $e){
    echo $e->getMessage();
}

echo "\n";