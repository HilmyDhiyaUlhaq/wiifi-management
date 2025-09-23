@extends('layouts.dashboard')

@section('dashboard')
    <div class="p-6 space-y-6">
        <h2 class="text-xl font-bold mb-4"> Transaksi Baru Untuk User {{ $user->name }}</h2>

        <form action="{{ route('users.transactions.store', ['userId' => $user->id]) }}" method="POST" id="transactionForm">
            @csrf
            <input type="hidden" name="packageId" id="selectedPackage">
            <input type="hidden" name="userId" value="{{ $user->id }}">

            {{-- Search packages --}}
            <div class="mb-2">
                <label for="packageSearch" class="block mb-1 font-medium">Cari Paket</label>
                <input id="packageSearch" type="text" placeholder="Cari nama, deskripsi, harga, atau hari…"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>

            <!-- Grid Packages -->
            <div id="packagesGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($packages as $package)
                    <div class="package-card border rounded-lg shadow hover:shadow-lg cursor-pointer transition p-4"
                        data-id="{{ $package->id }}" data-name="{{ Str::lower($package->name) }}"
                        data-description="{{ Str::lower($package->description ?? '') }}" data-price="{{ $package->price }}"
                        data-quota="{{ $package->quota }}">
                        <h3 class="text-lg font-semibold mb-2">{{ $package->name }}</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ $package->description ?? 'No description' }}</p>
                        <p class="text-blue-600 font-bold text-lg">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                        <p class="text-gray-700 text-sm">{{ $package->quota }} Days</p>
                    </div>
                @endforeach
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
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Konfirmasi Pilihan
                </button>
            </div>
        </form>
    </div>

    <script>
        const cards = document.querySelectorAll('.package-card');
        const selectedInput = document.getElementById('selectedPackage');
        const submitButton = document.getElementById('submitButton');

        cards.forEach(card => {
            card.addEventListener('click', () => {
                cards.forEach(c => c.classList.remove('border-blue-500', 'ring-2', 'ring-blue-300'));
                card.classList.add('border-blue-500', 'ring-2', 'ring-blue-300');
                selectedInput.value = card.dataset.id;
            });
        });

        // ====== Search di Grid Packages (tanpa jQuery) ======
        const packageSearch = document.getElementById('packageSearch');
        const noResults = document.getElementById('noResults');

        // util: normalisasi teks & angka
        const normalize = (s) => (s || '').toString().toLowerCase();
        const parseNumber = (s) => {
            // dukung input "100.000" -> 100000
            return Number(normalize(s).replace(/[^\d]/g, '')) || 0;
        };

        // debounce agar efisien
        function debounce(fn, wait = 150) {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), wait);
            };
        }

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
                const matchNumber = qNum > 0 && (
                    String(price).includes(qNum) || String(quota).includes(qNum)
                );

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
