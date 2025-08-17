{{-- resources/views/users/edit.blade.php --}}
@extends('layouts.dashboard')

@section('dashboard')
<div class="p-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Edit User</h1>

        {{-- Error global --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                <strong>Whoops!</strong> Please fix the errors below:
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="id" value="{{ $user->id }}">

            <!-- Grid Input -->
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <!-- Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required 
                        class="bg-gray-50 border @error('name') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required 
                        class="bg-gray-50 border @error('email') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                    <select id="role" name="role" required 
                        class="bg-gray-50 border @error('role') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">-- Select Role --</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                    <select id="status" name="status" required 
                        class="bg-gray-50 border @error('status') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">-- Select Status --</option>
                        <option value="active" {{ old('status', $user->userWifi?->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->userWifi?->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Password (optional update) -->
            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">New Password (leave blank if not changing)</label>
                <input type="password" id="password" name="password"
                    class="bg-gray-50 border @error('password') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="bg-gray-50 border @error('password_confirmation') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('password_confirmation') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">Cancel</a>
                <button type="submit" class="px-5 py-2.5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
