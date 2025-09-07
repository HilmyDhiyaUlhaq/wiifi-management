{{-- resources/views/packages/index.blade.php --}}
@extends('layouts.dashboard')
@section('dashboard')
    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Paket</h1>

            <div class="flex justify-between items-center mb-4">
                <form id="filterForm" method="GET" action="{{ route('packages.index') }}" class="flex gap-2 w-full">
                    <!-- Dropdown Per Page -->
                    <select name="perPage" class="rounded-lg border border-gray-300 text-sm p-2">
                        <option value="10" {{ $data['perPage'] == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $data['perPage'] == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $data['perPage'] == 50 ? 'selected' : '' }}>50</option>
                    </select>

                    <!-- Search Box -->
                    <input type="text" name="search" placeholder="Search packages..."
                        value="{{ $data['search'] ?? '' }}"
                        class="rounded-lg border border-gray-300 text-sm p-2 w-full max-w-xs ml-auto">

                    <!-- Tombol Export -->
                    <a type="submit" name="action" value="export" href="{{ route('packages.export') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                        Export
                    </a>

                    <!-- Tambah Package -->
                    <a href="{{ route('packages.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Tambahkan Paket Baru</a>
                </form>
            </div>

            <!-- Table -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="bg-blue-100">
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Harga</th>
                            <th scope="col" class="px-6 py-3">Kuota</th>
                            <th scope="col" class="px-6 py-3">Deskripsi</th>
                            <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $package)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $package->name }}
                                </th>
                                <td class="px-6 py-4">
                                    Rp. {{ number_format($package->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $package->quota }} Hari
                                </td>
                                <td class="px-6 py-4 whitespace-normal break-words max-w-xs">
                                    {{ $package->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('packages.edit', $package->id) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">Edit</a>
                                    <form action="{{ route('packages.destroy', $package->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $package->id }}">
                                        <button type="submit"
                                            class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                            onclick="return confirm('Are you sure you want to delete this package?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada paket yang ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $packages->links() }}
            </div>
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
