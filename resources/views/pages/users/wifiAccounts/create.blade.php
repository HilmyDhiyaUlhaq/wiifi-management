{{-- resources/views/users/create.blade.php --}}
@extends('layouts.dashboard')

@section('dashboard')
    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Add New WiFi Account</h1>

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

            <form action="{{ route('users.wifis.accounts.store', ['userId' => $user->id]) }}" method="POST">
                @csrf

                <!-- Grid Input -->
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full
                            Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="bg-gray-50 border @error('name') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="mac" class="block mb-2 text-sm font-medium text-gray-900">MAC</label>
                        <input type="text" id="mac" name="mac" required placeholder="AA:BB:CC:DD:EE:FF"
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
                    <a href="{{ route('users.wifis.accounts.index', ['userid' => $user->id]) }}"
                        class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">Cancel</a>
                    <button type="submit"
                        class="px-5 py-2.5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    (function() {
        const el = document.getElementById('mac');
        if (!el) return;

        const formatMac = (val) => {
            // ambil hanya HEX, jadikan uppercase, batasi 12 hex
            const hex = (val || '').replace(/[^0-9a-f]/gi, '').toUpperCase().slice(0, 12);
            // pecah per 2 dan gabung pakai :
            return (hex.match(/.{1,2}/g) || []).join(':');
        };

        // pertahankan posisi kursor yang wajar saat mengetik
        const placeCaret = (oldVal, newVal, oldPos) => {
            // hitung jumlah HEX sebelum kursor lama
            const hexBefore = (oldVal.slice(0, oldPos).match(/[0-9a-f]/gi) || []).length;
            // posisi baru = hexBefore + jumlah ':' yang muncul sebelum posisi itu
            const newPos = hexBefore + Math.floor(Math.max(hexBefore - 1, 0) / 2);
            requestAnimationFrame(() => {
                el.setSelectionRange(newPos, newPos);
            });
        };

        const onInput = () => {
            const oldVal = el.value;
            const oldPos = el.selectionStart || 0;
            const newVal = formatMac(oldVal);
            if (newVal !== oldVal) {
                el.value = newVal;
                placeCaret(oldVal, newVal, oldPos);
            }
        };

        el.addEventListener('input', onInput);
        el.addEventListener('paste', (e) => {
            // pasting: ambil text, format, lalu set
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            const formatted = formatMac(text);
            el.value = formatted;
            el.dispatchEvent(new Event('input'));
        });

        // format nilai awal (old('mac'))
        onInput();

        // pastikan terformat saat submit
        const form = el.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                el.value = formatMac(el.value);
            });
        }
    })();
</script>
