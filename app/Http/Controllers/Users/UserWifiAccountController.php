<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserWiFiAccountRepository;
use App\Repositories\Users\UserWiFiRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWifiAccountController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private UserWiFiRepository $userWiFiRepository,
        private UserWiFiAccountRepository $userWiFIAccountRepository
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
            'userWifiAccounts' => $this->userWiFIAccountRepository->getAllUserWiFIAccountByParams($data),
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
        $request->merge(['userId' => $userId]);
        $data = $request->validate([
            'userId' => 'required|exists:users,id,deleted_at,NULL',
            'mac' => 'required|string|unique:user_wifis_accounts,mac,deleted_at,NULL',
        ]);

        $userWifi = $this->userWiFiRepository->getUserWiFiByUserId($data['userId']);
        $data['user_wifi_id'] = $userWifi->id;
        $this->userWiFIAccountRepository->createUserWiFiAccount($data);

        return redirect()->route('users.wifis.accounts.index', ['userid' => $data['userId']]);
    }
}
