{{-- resources/views/users/create.blade.php --}}
@extends('layouts.dashboard')

@section('dashboard')
    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Tambahkan User Baru</h1>

            {{-- Error global --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                    <strong>Whoops!</strong> Tolong Perbaiki kesalahan di bawah ini:
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Tambahkan Akun Wifi Baru</h1>

                {{-- Error global --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                        <strong>Whoops!</strong> Tolong Perbaiki kesalahan di bawah ini:
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.wifis.accounts.store', ['userId' => $user->id]) }}" method="POST">
                    @csrf

                    <!-- Grid Input -->
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                Perangkat
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="bg-gray-50 border @error('name') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="mac" class="block mb-2 text-sm font-medium text-gray-900">MAC</label>
                            <input type="text" id="mac" name="mac" placeholder="AA:BB:CC:DD:EE:FF"
                                maxlength="17" autocomplete="off" inputmode="latin" value="{{ old('mac') }}"
                                class="bg-gray-50 border @error('mac') border-red-500 @else border-gray-300 @enderror
                                text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                block w-full p-2.5">
                            @error('mac')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>



                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('users.wifis.accounts.index', ['userId' => $user->id]) }}"
                            class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">Batalkan</a>
                        <button type="submit"
                            class="px-5 py-2.5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            document.getElementById('mac').addEventListener('input', function(e) {
                let value = e.target.value;

                // 1. Hilangkan semua karakter selain huruf a-f/A-F dan angka 0-9
                value = value.replace(/[^a-fA-F0-9]/g, '');

                // 2. Potong menjadi pasangan 2 karakter dan gabungkan dengan ':'
                let formatted = value.match(/.{1,2}/g)?.join(':') ?? '';

                // 3. Batasi maksimal 17 karakter (AA:BB:CC:DD:EE:FF)
                formatted = formatted.substring(0, 17);

                e.target.value = formatted.toUpperCase();
            });
        </script>
    @endsection
