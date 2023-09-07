<?php


use Wallex\Payout;

include __DIR__ . '/../vendor/autoload.php';

$pay = new Payout(1, 'secret_key');

$pay->cryptoPay('address', 100, 'USDT');
