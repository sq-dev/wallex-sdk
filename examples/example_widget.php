<?php

use Wallex\Widget;

include __DIR__.'/../vendor/autoload.php';

$widget = new Widget(1, '744d8аd1-d555-4fa1-a8c9-58fe91ee9bef');

echo $url = $widget->cretePayment(
    'client@mail.ru',
    'Xiaomi 9T',
    1000,
    1,
    'Hello thanks for order',
    'Xiaomi 9T',
    'USDT',
    'rub',
    'ru'
);