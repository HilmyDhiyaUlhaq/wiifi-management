<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Repositories\Packges\PackageRepository;
use App\Repositories\Transactions\TransactionUserPackageRepository;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserWiFiRepository;
use App\Services\ApplyQuotas\ApplyQuotaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTransactionController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private UserWiFiRepository $userWiFiRepository,
        private TransactionUserPackageRepository $transactionUserPackageRepository,
        private PackageRepository $packageRepository,
        private ApplyQuotaService $applyQuotaService
    ) {
        //
    }

    public function index(Request $request, $id)
    {
        $request->merge(['userId' => $id]);
        $data = $request->validate([
            'userId' => 'required|exists:users,id,deleted_at,NULL',
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'perPage' => 'integer|nullable',
        ]);
        $data['perPage'] = $data['perPage'] ?? 10;
        $data['type'] = 'package';

        return view('pages.users.transactions.index', [
            'transactionUserPackages' => $this->transactionUserPackageRepository->getAllTransactionUserPackageByParams($data),
            'user' => $this->userRepository->getUserById($data['userId']),
            'data' => $data
        ]);
    }

    public function create(Request $request, $userId)
    {
        $request->merge(['userId' => $userId]);
        $data = $request->validate([
            'userId' => 'required|exists:users,id,deleted_at,NULL'
        ]);

        return view('pages.users.transactions.create', [
            'user' => $this->userRepository->getUserById($data['userId']),
            'packages' => $this->packageRepository->getAllPackages()->where('type', 'package'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'packageId' => 'required|exists:packages,id,deleted_at,NULL',
            'userId' => 'required|exists:users,id,deleted_at,NULL'
        ]);
        $package = $this->packageRepository->getPackageById($data['packageId']);
        $user = $this->userRepository->getUserById($data['userId']);

        $status = Auth::user()?->role == 'admin' ? 'active' : 'request';

        $transactionData = [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'user_name' => $user->name,
            'description' => $package->description,
            'price' => $package->price,
            'quota' => $package->quota,
            'type' => $package->type,
            'created_by' => Auth::user()?->name,
            'status' => $status
        ];
        if ($status == 'active') {
            $transactionData['activation_at'] = Carbon::now();
        }
        $transactionData = $this->transactionUserPackageRepository->createTransactionUserPackage($transactionData);
        if ($status == 'active') {
            $this->applyQuotaService->applyPromo($transactionData->id, true);
        }
        return redirect()->route('users.transactions.index', ['userId' => $data['userId']])->with('success', 'Transaction added successfully.');
    }

    public function edit(Request $request, $userId, $id)
    {
        $request['id'] = $id;
        $request['userId'] = $userId;
        $data = $request->validate([
            'userId' => 'required|exists:users,id,deleted_at,NULL',
            'id' => 'required|exists:transactions_users_packages,id,deleted_at,NULL'
        ]);

        return view('pages.users.transactions.edit', [
            'user' => $this->userRepository->getUserById($data['userId']),
            'transactionUserPackage' => $this->transactionUserPackageRepository->getTransactionUserPackageById($id),
            'packages' => $this->packageRepository->getAllPackages()->where('type', 'package'),
        ]);
    }

    public function update(Request $request, $userId, $id)
    {
        $request['id'] = $id;
        $request['userId'] = $userId;
        $data = $request->validate([
            'id' => 'required|exists:transactions_users_packages,id,deleted_at,NULL,status,request',
            'packageId' => 'required|exists:packages,id,deleted_at,NULL',
            'userId' => 'required|exists:users,id,deleted_at,NULL',
            'status' => 'nullable|string'
        ]);

        $package = $this->packageRepository->getPackageById($data['packageId']);
        $user = $this->userRepository->getUserById($data['userId']);

        if (Auth::user()->role != 'admin') {
            $data['status'] = 'request';
        }

        $transactionData = [
            'user_id' => $data['userId'],
            'user_name' => $user->name,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'description' => $package->description,
            'price' => $package->price,
            'quota' => $package->quota,
            'status' => $data['status']
        ];
        if ($data['status'] == 'active') {
            $transactionData['activation_at'] = Carbon::now();
        }
        $this->transactionUserPackageRepository->updateTransactionUserPackageById($id, $transactionData);
        if ($transactionData['status'] == 'active') {
            $this->applyQuotaService->applyPromo($id);
        }
        return redirect()->route('users.transactions.index', ['userId' => $data['userId']])->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|exists:transactions_users_packages,id,deleted_at,NULL,status,request'
        ]);

        $this->transactionUserPackageRepository->deleteTransactionUserPackageById($id);

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

}
