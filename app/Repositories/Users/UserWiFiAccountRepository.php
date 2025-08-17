<?php
namespace App\Repositories\Users;

use App\Models\Users\UserWiFiAccount;

class UserWiFiAccountRepository
{
    public function getAllUserWiFIAccountByParams($params)
    {
        return UserWiFiAccount::leftJoin('users_wifis', 'users_wifis.id', '=', 'users_wifis_accounts.user_wifis_id')

            ->where(function ($query) use ($params) {
                $query->when(isset($params['userId']), function ($query) use ($params) {
                    $query->where('users_wifis.user_id', $params['userId']);
                });
            })->orderBy('created_at', 'desc')
            ->paginate($params['perPage'] ?? 10)
            ->appends([
                'search' => $params['search'] ?? null,
                'perPage' => $params['perPage'] ?? 10
            ]);
        ;
    }

    public function createUserWiFIAccount($data)
    {
        return UserWiFiAccount::create($data);
    }

    public function updateUserWiFIAccountById($id, $data)
    {
        return UserWiFiAccount::where('id', $id)->update($data);
    }

    public function deleteUserWiFIAccountById($id)
    {
        return UserWiFiAccount::where('id', $id)->forceDelete();
    }
}