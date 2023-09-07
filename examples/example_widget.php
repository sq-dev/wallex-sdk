<?php

use Wallex\Models\Product;
use Wallex\Widget;

include __DIR__.'/../vendor/autoload.php';

$widget = new Widget(1, 'secret_key');

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

$url = $widget->cretePayment($product);

echo $url.PHP_EOL;