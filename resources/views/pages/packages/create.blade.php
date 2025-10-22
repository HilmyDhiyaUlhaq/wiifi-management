{{-- resources/views/packages/create.blade.php --}}
@extends('layouts.dashboard')

@section('dashboard')
    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Tambahkan Paket Baru</h1>

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

            <form action="{{ route('packages.store') }}" method="POST">
                @csrf

                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Paket</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="bg-gray-50 border @error('name') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Quota --}}
                    <div>
                        <label for="quota" class="block mb-2 text-sm font-medium text-gray-900">Kuota</label>
                        <input type="text" id="quota_display" value="{{ old('quota') ? $package->quota . ' Hari' : '' }}"
                            class="bg-gray-50 border @error('quota') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <input type="hidden" id="quota" name="quota" value="{{ old('quota') }}">
                        @error('quota')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- kind --}}
                    <div>
                        <label for="kind" class="block mb-2 text-sm font-medium text-gray-900">Jenis</label>
                        <select name="kind" id="kind"
                            class="g-gray-50 border @error('price') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            autocomplete="off">
                            <option value="">Pilih Jenis ...</option>
                            @foreach ($kinds as $kind)
                                <option {{ old('kind') == $kind ? 'selected' : '' }} value="{{ $kind }}">
                                    {{ $kind }}</option>
                            @endforeach
                        </select>
                        @error('kind')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Harga</label>
                        <input type="text" id="price_display" value="{{ old('price') }}"
                            class="bg-gray-50 border @error('price') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <input type="hidden" id="price" name="price" value="{{ old('price') }}">
                        @error('price')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                        class="bg-gray-50 border @error('description') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('packages.index') }}"
                        class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">Batalkan</a>
                    <button type="submit"
                        class="px-5 py-2.5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS Format --}}
    <script>
        // Format angka jadi Rupiah
        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah ? 'Rp. ' + rupiah + (split[1] ? ',' + split[1] : '') : '';
        }

        // Price
        const priceDisplay = document.getElementById('price_display');
        const priceHidden = document.getElementById('price');
        priceDisplay.addEventListener('input', function() {
            let clean = this.value.replace(/[^0-9]/g, '');
            priceHidden.value = clean;
            this.value = formatRupiah(clean);
        });

        // Quota
        const quotaDisplay = document.getElementById('quota_display');
        const quotaHidden = document.getElementById('quota');
        quotaDisplay.addEventListener('input', function() {
            let clean = this.value.replace(/[^0-9]/g, '');
            quotaHidden.value = clean;
            this.value = clean ? clean + ' Hari' : '';
        });
    </script>
@endsection
