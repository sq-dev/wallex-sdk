# Wallex SDK

## Project Overview
The Wallex SDK is a software development kit that provides a set of tools and utilities for integrating with the Wallex payment gateway.

## Table of Contents
- [Getting Started](#getting-started)
  - [Installation](#installation)
- [Features](#features)
- [Contributing](#contributing)
- [License](#license)

## Getting Started
This section provides instructions on how to get started with the Wallex SDK.

### Installation
```bash
composer require sq-dev/wallex-sdk
```

## Features
This section describes the main features and capabilities of the Wallex SDK.

### Feature 1: Create payment widget

```php
use Wallex\Widget;

$widget = new Widget(1, 'secret_key');

$url = $widget->cretePayment(
  'client@mail.ru',
  'Xiaomi 9T',
  1000,
  1,
  'Hello thanks for order',
  'Xiaomi 9T',
  'USDT',
  'rub',
  'ru'
); // Returns payment url
```
More information about the parameters can be found in the [documentation](https://wallex.online/api_for_payments#instr-api-pop).

### Feature 2: Verify payment

```php
use Wallex\Webhook;

$payment = new Webhook($_POST);
if ($payment->isVerified('secret_key') && $payment->isSuccess()) {
    // Payment success logic
    //F.e:
    $client = $payment->getClient(); // Get client email
    User::where('email', $client)
        ->update(['balance' => $payment->getAmount()]);
}
``` 
### Feature 3: Payouts

```php
use Wallex\Payout;

$payout = new Payout($merchantId, $secretKey);

$payout->cryptoPay($address, $amount, $currency);

```
More information about the parameters can be found in the [documentation](https://wallex.online/api_for_payments).

## Contributing
We welcome contributions from the developer community to improve the Wallex SDK. If you are interested in contributing to the Wallex SDK, please follow the steps below:

1. Fork the repository on GitHub.
2. Create a new branch for your feature or bug fix.
3. Make the necessary changes in your branch.
4. Write tests to ensure the changes are working as expected.
5. Submit a pull request with your changes.

## License
The Wallex SDK is licensed under the [MIT License](LICENSE).
