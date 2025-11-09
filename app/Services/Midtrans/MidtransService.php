<?php
namespace App\Services\Midtrans;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = (bool) config('services.midtrans.sanitize', true);
        Config::$is3ds = (bool) config('services.midtrans.3ds', true);
    }

    public function getSnapToken(array $params): string
    {
        if (isset($params['transaction_details']['gross_amount'])) {
            $params['transaction_details']['gross_amount'] =
                (int) $params['transaction_details']['gross_amount'];
        }

        $orderId = (string) ($params['transaction_details']['order_id'] ?? '');
        if ($orderId === '') {
            throw new \InvalidArgumentException('transaction_details.order_id wajib diisi');
        }

        return Snap::getSnapToken($params);
    }
}
