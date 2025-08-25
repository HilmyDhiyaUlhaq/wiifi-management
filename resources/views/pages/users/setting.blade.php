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

            {{-- Tambah enctype untuk upload file --}}
            <form action="{{ route('users.setting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="bg-gray-50 border @error('name') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                            required
                            class="bg-gray-50 border @error('email') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div class="md:col-span-2">
                        <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Foto (opsional)</label>

                        @if ($user->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $user->image) }}" alt="Current Photo"
                                    class="h-24 w-24 object-cover rounded-lg border">
                            </div>
                        @endif

                        <input type="file" id="image" name="image" accept="image/*"
                            class="bg-gray-50 border @error('image') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div id="photoPreviewWrap" class="mt-3 hidden">
                            <p class="text-sm text-gray-600 mb-1">Preview:</p>
                            <img id="photoPreview" class="h-24 w-24 object-cover rounded-lg border" />
                        </div>
                    </div>
                </div>

                {{-- Password (opsional) --}}
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">
                        New Password (leave blank if not changing)
                    </label>
                    <input type="password" id="password" name="password"
                        class="bg-gray-50 border @error('password') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">
                        Confirm New Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="bg-gray-50 border @error('password_confirmation') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    @error('password_confirmation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('users.index') }}"
                        class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">Cancel</a>
                    <button type="submit"
                        class="px-5 py-2.5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS preview sederhana --}}
    <script>
        document.getElementById('image')?.addEventListener('change', function(e) {
            const file = e.target.files?.[0];
            const wrap = document.getElementById('photoPreviewWrap');
            const img = document.getElementById('photoPreview');
            if (!file) {
                wrap.classList.add('hidden');
                return;
            }
            const reader = new FileReader();
            reader.onload = () => {
                img.src = reader.result;
                wrap.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    </script>
@endsection
