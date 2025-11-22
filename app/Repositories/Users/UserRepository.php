<?php
namespace App\Repositories\Users;

use App\Models\Users\User;

class UserRepository
{

    public function getAllUsersByParams($params)
    {
        return User::with(['userWifi'])
            ->where(function ($query) use ($params) {
                $query->when(isset($params['search']), function ($query) use ($params) {
                    $query->where(function ($query) use ($params) {
                        $query->whereRaw('lower(name) like lower(?)', ["%{$params['search']}%"]);
                        $query->orWhereRaw('lower(email) like lower(?)', ["%{$params['search']}%"]);
                        $query->orWhereRaw('lower(role) like lower(?)', ["%{$params['search']}%"]);
                    });
                });
            })->orderBy('name', 'asc')->paginate($params['perPage'] ?? 10)
            ->appends([
                'search' => $params['search'] ?? null,
                'per_page' => $params['perPage'] ?? null
            ]);
    }
    public function createUser($data)
    {
        return User::restoreOrCreate(['email' => $data['email']], $data);
    }
    public function updateuserById($id, $data)
    {
        return User::where('id', $id)->update($data);
    }
    public function deleteUserById($id)
    {
        return User::where('id', $id)->delete();
    }
    public function getUserEmail($email)
    {
        return User::where('email', $email)->first();
    }
    public function getUserById($id)
    {
        return User::with(['userWifi'])->find($id);
    }

    public function getAllUsers()
    {
        return User::all();
    }
}
