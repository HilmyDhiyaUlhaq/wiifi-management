<?php

namespace App\Http\Controllers\Transactions;

use App\Exports\TransactionUserPackageExport;
use App\Http\Controllers\Controller;
use App\Repositories\Packges\PackageRepository;
use App\Repositories\Transactions\TransactionUserPackageRepository;
use App\Repositories\Users\UserRepository;
use App\Services\ApplyQuotas\ApplyQuotaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionUserPackageController extends Controller
{
    public function __construct(
        private PackageRepository $packageRepository,
        private UserRepository $userRepository,
        private TransactionUserPackageRepository $transactionUserPackageRepository,
        private ApplyQuotaService $applyQuotaService
    ) {
        //
    }

    public function index(Request $request)
    {
        $data = $request->validate([
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'perPage' => 'integer|nullable',
            'startDate' => 'date|nullable',
            'endDate' => 'date|nullable',
        ]);
        $data['perPage'] = $data['perPage'] ?? 10;

        if (!isset($data['startDate'])) {
            $data['startDate'] = Carbon::now()->startOfMonth()->format('Y-m-d');
        }

        if (!isset($data['endDate'])) {
            $data['endDate'] = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $data['type'] = 'package';
        return view('pages.transactions.index', [
            'transactionUserPackages' => $this->transactionUserPackageRepository->getAllTransactionUserPackageByParams($data),
            'data' => $data,
            'total' => (float) $this->transactionUserPackageRepository->getCountTransactionUserPackageByParams($data)
        ]);
    }

    public function create(Request $request)
    {
        return view('pages.transactions.create', [
            'users' => $this->userRepository->getAllUsers(),
            'packages' => $this->packageRepository->getAllPackages()->where('type', 'package'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'packageId' => 'required|exists:packages,id,deleted_at,NULL',
            'userId' => 'required|exists:users,id,deleted_at,NULL',
            'paymentMethod' => 'required|string'
        ]);
        $data['status'] = strtolower($data['paymentMethod']) == 'cash' ? 'active' : 'request';
        $package = $this->packageRepository->getPackageById($data['packageId']);
        $user = $this->userRepository->getUserById($data['userId']);
        $transactionData = [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'user_name' => $user->name,
            'description' => $package->description,
            'price' => $package->price,
            'quota' => $package->quota,
            'status' => $data['status'],
            'type' => $package->type,
            'kind' => $package->kind,
            'activation_at' => $data['status'] == 'active' ? Carbon::now() : null,
            'created_by' => Auth::user()?->name
        ];
        $transactionData = $this->transactionUserPackageRepository->createTransactionUserPackage($transactionData);

        $this->applyQuotaService->applyPromo($transactionData->id, true);
        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully.');
    }

    public function edit(Request $request, $id)
    {
        $request['id'] = $id;
        $request->validate([
            'id' => 'required|exists:transactions_users_packages,id,deleted_at,NULL'
        ]);

        return view('pages.transactions.edit', [
            'transactionUserPackage' => $this->transactionUserPackageRepository->getTransactionUserPackageById($id),
            'packages' => $this->packageRepository->getAllPackages()->where('type', 'package'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request['id'] = $id;
        $data = $request->validate([
            'id' => 'required|exists:transactions_users_packages,id,deleted_at,NULL,status,request',
            'packageId' => 'required|exists:packages,id,deleted_at,NULL',
            'userId' => 'required|exists:users,id,deleted_at,NULL',
            'paymentMethod' => 'required|string'
        ]);
        $data['status'] = strtolower($data['paymentMethod']) == 'cash' ? 'active' : 'request';
        $package = $this->packageRepository->getPackageById($data['packageId']);
        $user = $this->userRepository->getUserById($data['userId']);

        $transactionData = [
            'user_id' => $data['userId'],
            'user_name' => $user->name,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'description' => $package->description,
            'price' => $package->price,
            'quota' => $package->quota,
            'status' => $data['status'],
            'type' => $package->type,
            'activation_at' => $data['status'] == 'active' ? Carbon::now() : null,
            'created_by' => Auth::user()?->name,
            'kind' => $package->kind,
            'payment_method' => $data['paymentMethod']
        ];
        $this->transactionUserPackageRepository->updateTransactionUserPackageById($id, $transactionData);

        if (strtolower($data['paymentMethod']) == 'cash') {
            if ($data['status'] == 'active') {
                $this->applyQuotaService->applyPromo($id);
            }
            return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
        } else {
            return redirect()->route('payments.show', ['id' => $id, 'url' => route('transactions.index')])->with('success', 'Transaction deleted successfully.');
        }
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|exists:transactions_users_packages,id,deleted_at,NULL'
        ]);

        $this->transactionUserPackageRepository->deleteTransactionUserPackageById($id);

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    public function export(Request $request)
    {
        $data = $request->validate([
            'startDate' => 'date|nullable',
            'endDate' => 'date|nullable',
        ]);

        if (!isset($data['startDate'])) {
            $data['startDate'] = Carbon::now()->startOfMonth()->format('Y-m-d');
        }

        if (!isset($data['endDate'])) {
            $data['endDate'] = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $data['type'] = 'package';
        return Excel::download(new TransactionUserPackageExport($data), 'export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

}
