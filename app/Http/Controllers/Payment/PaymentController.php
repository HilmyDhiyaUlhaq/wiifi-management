<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Repositories\Transactions\TransactionUserPackageRepository;
use App\Services\Midtrans\MidtransService;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class PaymentController extends Controller
{
    public function __construct(
        private TransactionUserPackageRepository $transactionUserPackageRepository,
        private MidtransService $midtransService,
    ) {
        //
    }

    public function show(Request $request, $transactionUserPackageId)
    {
        $request['transactionUserPackageId'] = $transactionUserPackageId;
        $request->validate([
            'transactionUserPackageId' => 'required|exists:transactions_users_packages,id,deleted_at,NULL,status,request',
            'url' => 'required|string'
        ]);
        $this->transactionUserPackageRepository->updateTransactionUserPackageById($transactionUserPackageId, ['order_id' => Uuid::uuid4()->toString()]);
        $transactionUserPackage = $this->transactionUserPackageRepository->getTransactionUserPackageById($transactionUserPackageId);
        $requestSnapToken = [
            'transaction_details' => [
                'order_id' => $transactionUserPackage->order_id,
                'gross_amount' => $transactionUserPackage->price,
            ],
            'customer_details' => [
                'first_name' => optional($transactionUserPackage->user)->name,
                'email' => optional($transactionUserPackage->user)->email,
            ],
            'enabled_payments' => ['qris', 'bank_transfer', 'credit_card', 'gopay', 'shopeepay'],
            'callbacks' => [
                'finish' => url($request['url']),
            ],
            'expiry' => [
                'unit' => 'minutes',
                'duration' => 15,
            ],
        ];
        return view('pages.payments.show', [
            'transactionUserPackage' => $transactionUserPackage,
            'snapToken' => $this->midtransService->getSnapToken($requestSnapToken)
        ]);
    }
}
