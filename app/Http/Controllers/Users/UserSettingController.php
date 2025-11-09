<?php

namespace App\Http\Controllers\Users;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserWiFiRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserSettingController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private UserWiFiRepository $userWiFiRepository
    ) {
        //
    }

    public function edit()
    {
        return view('pages.users.setting', [
            'user' => Auth::user()
        ]);
    }
    public function update(Request $request)
    {
        $user = $this->userRepository->getUserById(Auth::user()->id);
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string',
            'password_confirmation' => ['required_with:password', 'string', 'nullable', 'same:password'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('image')) {
            if (!empty($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('image')->store('users/' . $user->id . '/image', 'public');
            $payload['image'] = $path;
        }

        $this->userRepository->updateuserById($user->id, $payload);

        return redirect()->route('users.setting.edit')->with('success', 'Your settings have been updated.');
        ;
    }
}
