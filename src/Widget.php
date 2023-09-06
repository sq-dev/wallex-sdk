<?php

namespace Wallex;

use Wallex\Abstracts\WallexClient;
use Wallex\Models\Product;

class Widget extends WallexClient
{
    protected const WIDGET_URL = 'https://wallex.online/widget/%d?data=%s';

    /**
     * Создание платежа
     *
     * @param Product $product - Объект продукта
     * @return string - URL для оплаты
     */
    public function cretePayment(
        Product $product
    ): string
    {
        $data = $product->toArray();

        $data['sign'] = $this->calculateSign($data);
        $data = http_build_query($data);

        return sprintf(self::WIDGET_URL, $this->merchantId, base64_encode($data));
    }
}