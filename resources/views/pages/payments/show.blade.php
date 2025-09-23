@extends('layouts.dashboard')

@section('dashboard')
    <div class="p-6 space-y-6">
        <h2 class="text-xl font-bold mb-4">Konfirmasi Pembayaran</h2>

        <div class="rounded-xl border p-4 grid gap-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-semibold">{{ $transactionUserPackage->package_name }}</div>
                    <div class="text-sm text-gray-500">{{ $transactionUserPackage->description }}</div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold">
                        Rp {{ number_format($transactionUserPackage->price, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500">Kuota: {{ $transactionUserPackage->quota }}</div>
                </div>
            </div>

            <button id="pay-button"
                class="mt-3 inline-flex items-center justify-center rounded-lg px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50">
                Bayar Sekarang
            </button>

            <p id="pay-help" class="text-xs text-gray-500">
                Anda akan diarahkan ke halaman pembayaran Midtrans (aman & terenkripsi).
            </p>
        </div>
    </div>

    {{-- Snap.js (gunakan client key dari config/services.midtrans.client_key) --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        const payBtn = document.getElementById('pay-button');
        const snapToken = @json($snapToken);
        const trxId = @json($transactionUserPackage->id);

        payBtn?.addEventListener('click', function() {
            if (!snapToken) return;

            // Panggil Snap
            window.snap.pay(snapToken);
        });
    </script>
@endsection
