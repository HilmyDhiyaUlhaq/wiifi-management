<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Jobs\SyncLeasesDhcpJob;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserWiFiAccountRepository;
use App\Repositories\Users\UserWiFiRepository;
use App\Services\Mikrotiks\ConnectioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserWifiAccountController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private UserWiFiRepository $userWiFiRepository,
        private UserWiFiAccountRepository $userWiFiAccountRepository,
        private ConnectioService $connectioService
    ) {
        //
    }

    public function index(Request $request, $userId)
    {
        $request->merge(['userId' => $userId]);
        $data = $request->validate([
            'userId' => 'required|exists:users,id,deleted_at,NULL',
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'perPage' => 'integer|nullable'
        ]);
        $data['perPage'] = $data['perPage'] ?? 10;

        return view('pages.users.wifiAccounts.index', [
            'userWifiAccounts' => $this->userWiFiAccountRepository->getAllUserWiFIAccountByParams($data),
            'user' => $this->userRepository->getUserById($data['userId']),
            'data' => $data
        ]);
    }

    public function create(Request $request, $userId)
    {
        $user = $this->userRepository->getUserById($userId);
        return view('pages.users.wifiAccounts.create', [
            'user' => $user,
            'wifi' => $this->userWiFiRepository->getUserWiFiByUserId($user->id)
        ]);
    }

    public function store(Request $request, $userId)
    {
        try {
            DB::beginTransaction();
            $request->merge(['userId' => $userId]);
            $data = $request->validate([
                'userId' => 'required|exists:users,id,deleted_at,NULL',
                'mac' => 'required|string|unique:users_wifis_accounts,mac',
                'name' => 'string|required',
            ]);

            $userWifi = $this->userWiFiRepository->getUserWiFiByUserId($data['userId']);
            $userWiFiAccount = $this->userWiFiAccountRepository->createUserWiFiAccount([
                'user_wifi_id' => $userWifi->id,
                'mac' => $data['mac'],
                'name' => $data['name']
            ]);

            SyncLeasesDhcpJob::dispatch($userWiFiAccount->id);
            DB::commit();

            return redirect()->route('users.wifis.accounts.index', ['userId' => $data['userId']]);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $userId, $id)
    {
        $request['id'] = $id;
        $request->validate([
            'id' => 'required|exists:users_wifis_accounts,id,deleted_at,NULL',
        ]);

        $this->connectioService->deleteLeasesDhcp($id);
        $this->userWiFiAccountRepository->deleteUserWiFiAccountById($id);
        return redirect()->route('users.wifis.accounts.index', ['userId' => $userId]);
    }

    public function syncLeases(Request $request, $userId, $id)
    {
        $request['id'] = $id;
        $request->validate([
            'id' => 'required|exists:users_wifis_accounts,id,deleted_at,NULL,status,NOT-SYNC',
        ]);

        try {
            $this->connectioService->setLeasesDhcp($id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route('users.wifis.accounts.index', ['userId' => $userId])->with('error', 'Sync data gagal, silakan coba lagi nanti');
        }
        return redirect()->route('users.wifis.accounts.index', ['userId' => $userId])->with('success', 'Lease synchronizado correctamente');
    }
}
