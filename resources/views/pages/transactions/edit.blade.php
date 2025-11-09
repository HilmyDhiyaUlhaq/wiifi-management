@extends('layouts.dashboard')

@section('dashboard')
    @php
        // asumsi: kolom yg menyimpan package terpilih = package_id
        $currentPackageId = $transactionUserPackage->package_id ?? null;
    @endphp
    <div class="p-6 space-y-6">
        <h2 class="text-xl font-bold mb-4">
            Edit Transaksi Paket Untuk Pengguna {{ $transactionUserPackage?->user?->name }}
        </h2>

        <form action="{{ route('transactions.update', ['id' => $transactionUserPackage->id]) }}" method="POST"
            id="transactionForm">
            @csrf
            @method('PATCH')

            <input type="hidden" name="packageId" id="selectedPackage" value="{{ $currentPackageId }}">
            <input type="hidden" name="userId" value="{{ $transactionUserPackage->user_id }}">

            {{-- Search packages --}}
            <div class="mb-2">
                <label for="packageSearch" class="block mb-1 font-medium">Cari Paket</label>
                <input id="packageSearch" type="text" placeholder="Cari nama, deskripsi, harga, atau hari…"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>

            <!-- Grid Packages -->
            <div id="packagesGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($packages as $package)
                    @php
                        $isActive = (string) $currentPackageId === (string) $package->id;
                    @endphp
                    <button type="button"
                        class="package-card border rounded-lg shadow hover:shadow-lg cursor-pointer transition p-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-300
                               {{ $isActive ? 'border-blue-500 ring-2 ring-blue-300' : '' }}"
                        data-id="{{ $package->id }}" data-name="{{ strtolower($package->name) }}"
                        data-description="{{ strtolower($package->description ?? '') }}" data-price="{{ $package->price }}"
                        data-quota="{{ $package->quota }}" aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                        <h3 class="text-lg font-semibold mb-2">{{ $package->name }}</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ $package->description ?? 'No description' }}</p>
                        <p class="text-blue-600 font-bold text-lg">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                        <p class="text-gray-700 text-sm">{{ $package->quota }} Days</p>
                    </button>
                @endforeach
            </div>

            <!-- No results -->
            <div id="noResults" class="hidden mt-4 text-center text-gray-600">
                Tidak ada paket yang cocok dengan kata kunci.
            </div>

            <!-- Select Payment -->
            <div class="my-4">
                <label for="paymentMethod" class="block mb-2 text-sm font-medium text-gray-900">Metode Pembayaran</label>
                <select id="paymentMethod" name="paymentMethod" required
                    class="bg-gray-50 border @error('paymentMethod') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="Online" {{ old('paymentMethod') == 'Online' ? 'selected' : '' }}>Online</option>
                    <option value="Cash" {{ old('paymentMethod') == 'Cash' ? 'selected' : '' }}>Langsung
                    </option>
                </select>
                @error('paymentMethod')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="mt-6 text-right">
                <button type="submit" id="submitButton"
                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    Konfirmasi Pilihan
                </button>
            </div>
        </form>
    </div>

    <script>
        // Elemen2 yang dipakai
        const cards = document.querySelectorAll('.package-card');
        const selectedInput = document.getElementById('selectedPackage');
        const submitButton = document.getElementById('submitButton');
        const packageSearch = document.getElementById('packageSearch');
        const packagesGrid = document.getElementById('packagesGrid');
        const noResults = document.getElementById('noResults');

        // Helper
        const normalize = (s) => (s || '').toString().toLowerCase();
        const parseNumber = (s) => Number(normalize(s).replace(/[^\d]/g, '')) || 0;

        function debounce(fn, wait = 150) {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), wait);
            };
        }

        // Seleksi kartu
        function applySelection(card) {
            cards.forEach(c => {
                c.classList.remove('border-blue-500', 'ring-2', 'ring-blue-300');
                c.setAttribute('aria-pressed', 'false');
            });
            card.classList.add('border-blue-500', 'ring-2', 'ring-blue-300');
            card.setAttribute('aria-pressed', 'true');
            selectedInput.value = card.dataset.id;
            maybeToggleSubmit();
        }

        cards.forEach(card => {
            card.addEventListener('click', () => applySelection(card));
            // aksesibilitas keyboard
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    applySelection(card);
                }
            });
            card.setAttribute('tabindex', '0');
        });

        // Enable/disable submit jika wajib pilih
        function maybeToggleSubmit() {
            // Jika ingin wajib pilih, aktifkan baris di bawah:
            // submitButton.disabled = !selectedInput.value;
        }
        maybeToggleSubmit();

        // Filter pencarian
        const filterCards = () => {
            const q = normalize(packageSearch ? packageSearch.value : '');
            const qNum = parseNumber(q);
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.dataset.name || '';
                const desc = card.dataset.description || '';
                const price = Number(card.dataset.price || 0);
                const quota = Number(card.dataset.quota || 0);

                const matchText = name.includes(q) || desc.includes(q);
                const matchNumber = qNum > 0 && (String(price).includes(qNum) || String(quota).includes(qNum));
                const isMatch = q.length === 0 ? true : (matchText || matchNumber);

                card.classList.toggle('hidden', !isMatch);
                if (isMatch) visibleCount++;
            });

            if (packagesGrid) packagesGrid.classList.toggle('hidden', visibleCount === 0);
            if (noResults) noResults.classList.toggle('hidden', visibleCount !== 0);
        };

        if (packageSearch) {
            packageSearch.addEventListener('input', debounce(filterCards, 120));
            filterCards();
        }
    </script>
@endsection
