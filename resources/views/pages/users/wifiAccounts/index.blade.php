@php
    use Illuminate\Support\Facades\Auth;
@endphp
@extends('layouts.dashboard')
@section('dashboard')
    <div class="p-6 space-y-6">
        <div>
            <a href="{{ route('users.transactions.index', ['userId' => $user->id]) }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">Transaksi</a>
            <a href="{{ route('users.wifis.accounts.index', ['userId' => $user->id]) }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">Akun Wifi</a>
        </div>
        {{-- Summary Card --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-4 text-sm text-gray-700">
                <div><span class="font-semibold">Nama :</span> {{ $user->name }}</div>
                <div><span class="font-semibold">Email :</span> {{ $user->email }}</div>
                <div>
                    <span class="font-semibold">Status :</span>
                    <span
                        class="px-2 py-1 text-xs rounded-full  {{ $user->userWifi?->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($user->userWifi?->status) }}
                    </span>
                </div>
                <div><span class="font-semibold">Kuota :</span> {{ $user->userWifi?->count_quota }} Hari</div>
                <div><span class="font-semibold">Peran :</span> {{ ucfirst($user->role ?? '-') }}</div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-4">
            <form id="filterForm" method="GET" action="{{ route('users.index') }}" class="flex gap-2 w-full">
                <!-- Dropdown Per Page -->
                <select name="perPage" class="rounded-lg border border-gray-300 text-sm p-2">
                    <option value="10" {{ $data['perPage'] == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $data['perPage'] == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $data['perPage'] == 50 ? 'selected' : '' }}>50</option>
                </select>

                <!-- Search Box -->
                <input type="text" name="search" placeholder="Search users..." value="{{ $data['search'] ?? '' }}"
                    class="rounded-lg border border-gray-300 text-sm p-2 w-full max-w-xs ml-auto">

                <!-- Tombol Export -->
                <button type="submit" name="action" value="export"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                    Export
                </button>

                <!-- Tambah User -->
                <a href="{{ route('users.wifis.accounts.create', ['userId' => $user->id]) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Tambahkan Akun Baru</a>
            </form>
        </div>
        {{-- Table Card --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="bg-blue-100">
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">IP</th>
                        <th scope="col" class="px-6 py-3">Mac</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($userWifiAccounts as $userWifiAccount)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $userWifiAccount->name }}
                            </td>
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $userWifiAccount->ip }}
                            </td>
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $userWifiAccount->mac }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form class="{{ $userWifiAccount->status == 'SYNC' ? 'hidden' : 'inline' }}"
                                    action="{{ route('users.wifis.accounts.sync', ['userId' => $user->id, 'id' => $userWifiAccount->id]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="id" value="{{ $userWifiAccount->id }}">
                                    <button type="submit"
                                        class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline"
                                        onclick="return confirm('Are you sure you want to sync this account?')">
                                        Sync Data
                                    </button>
                                </form>
                                <form
                                    action="{{ route('users.wifis.accounts.destroy', ['userId' => $user->id, 'id' => $userWifiAccount->id]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $userWifiAccount->id }}">
                                    <button type="submit"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                        onclick="return confirm('Are you sure you want to delete this package?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada paket yang ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        const searchInput = form.querySelector('input[name="search"]');
        const perPageSelect = form.querySelector('select[name="perPage"]');
        let typingTimer;

        // Auto-submit saat ganti jumlah per halaman
        perPageSelect.addEventListener('change', function() {
            form.submit();
        });

        // Auto-submit search setelah berhenti mengetik
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => form.submit(), 500);
        });
    });
</script>
