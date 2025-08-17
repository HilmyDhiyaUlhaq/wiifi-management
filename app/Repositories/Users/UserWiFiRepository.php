<?php
namespace App\Repositories\Users;

use App\Models\Users\UserWiFi;

class UserWiFiRepository
{
    public function createUserWiFi($data)
    {
        return UserWiFi::create($data);
    }

    public function updateUserWiFiByUserId($userId, $data)
    {
        return UserWiFi::where('user_id', $userId)->update($data);
    }

    public function deleteUserWiFiByUserId($userId)
    {
        return UserWiFi::where('user_id', $userId)->delete();
    }

    public function getUserWiFiByUserId($userId)
    {
        return UserWiFi::where('user_id', $userId)->first();
    }
}