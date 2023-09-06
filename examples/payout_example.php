<?php


use Wallex\Payout;

include __DIR__ . '/../vendor/autoload.php';

$widget = new Payout(220, '744d8cd1-d444-4fa1-a6c9-58fe91ee6bef', 'ca92f945ba65efe9159ec14fbdaf8760');

print_r($widget->cryptoPay(100, 100, 'USDT'));
//var_dump($widget->getAll());

