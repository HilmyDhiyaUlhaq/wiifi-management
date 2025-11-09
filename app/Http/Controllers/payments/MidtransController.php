<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use App\Models\Transaction\TransactionUserPackage;
use App\Repositories\Transactions\TransactionUserPackageRepository;
use App\Services\ApplyQuotas\ApplyQuotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function __construct(private TransactionUserPackageRepository $transactionUserPackageRepository, private ApplyQuotaService $applyQuotaService)
    {
        //
    }
    public function __invoke(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');

        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');

        // Verifikasi signature
        $raw = $orderId . $statusCode . $grossAmount . $serverKey;
        $expectedSignature = hash('sha512', $raw);

        if (!hash_equals($expectedSignature, (string) $signatureKey)) {
            Log::warning('Midtrans signature mismatch', ['order_id' => $orderId]);
            return response()->json(['message' => 'invalid signature'], 403);
        }

        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        // Temukan transaksi kita dari order_id
        $trx = $this->transactionUserPackageRepository->getTransactionUserPackageByOrderId($orderId);
        if (!$trx) {
            Log::warning('Midtrans notify: order not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'order not found'], 404);
        }

        // Map status Midtrans -> status aplikasi
        $newStatus = match ($transactionStatus) {
            'capture' => ($fraudStatus === 'challenge') ? 'challenge' : 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny' => 'failed',
            'cancel' => 'canceled',
            'expire' => 'expired',
            'refund', 'partial_refund' => 'refunded',
            default => $trx->status,
        };
        try {
            if ($newStatus == 'paid') {
                if ($trx->type == 'package') {
                    $this->applyQuotaService->applyPromo($trx->id);
                }
                $this->transactionUserPackageRepository->updateTransactionUserPackageById($trx->id, ['status' => 'active']);
            } else {
                $this->transactionUserPackageRepository->updateTransactionUserPackageById($trx->id, ['status' => $newStatus]);
            }
            return response()->json(['message' => 'ok']);
        } catch (\Exception $e) {
            $this->transactionUserPackageRepository->updateTransactionUserPackageById($trx->id, ['status' => 'failed']);
            return response()->json(['message' => 'failed'], 500);
        }


    }
}
