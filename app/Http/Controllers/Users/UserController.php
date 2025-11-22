<?php

namespace App\Http\Controllers\Users;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserWiFiRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private UserWiFiRepository $userWiFiRepository
    ) {
        //
    }
    public function index(Request $request)
    {
        $data = $request->validate([
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'perPage' => 'integer|nullable'
        ]);
        $data['perPage'] = $data['perPage'] ?? 10;

        return view('pages.users.index', [
            'users' => $this->userRepository->getAllUsersByParams($data),
            'data' => $data
        ]);
    }
    public function create()
    {
        return view('pages.users.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'role' => 'required|string|in:admin,user',
            'password' => 'required|string',
            'password_confirmation' => 'required|string|same:password',
            'status' => 'required|string|in:active,inactive'
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->createUser([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => $data['password']
        ]);

        // create user wifi
        $this->userWiFiRepository->createUserWiFi([
            'user_id' => $user->id,
            'status' => $data['status'] ?? 'inactive',
            'count_quota' => 0
        ]);

        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        return view('pages.users.edit', [
            'user' => $this->userRepository->getUserById($id)
        ]);
    }
    public function update(Request $request, $id)
    {
        $user = $this->userRepository->getUserById($id);
        $data = $request->validate([
            'id' => 'required|exists:users,id,deleted_at,NULL',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string|in:admin,user',
            'password' => 'nullable|string',
            'password_confirmation' => ['required_with:password', 'string', 'nullable', 'same:password'],
            'status' => 'required|string|in:active,inactive'
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            $data['password'] = $user->password;
        }

        $this->userRepository->updateuserById($id, [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => $data['password'],
        ]);

        // update user wifi
        $this->userWiFiRepository->updateUserWiFiByUserId($user->id, [
            'status' => $data['status'] ?? 'inactive',
        ]);

        return redirect()->route('users.index');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|exists:users,id,deleted_at,NULL'
        ]);

        $this->userRepository->deleteUserById($id);
        return redirect()->route('users.index');
    }

    public function export()
    {
        return Excel::download(new UserExport, 'user.xlsx', \Maatwebsite\Excel\Excel::XLSX);

    }
}
