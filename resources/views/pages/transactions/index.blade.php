@php
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;
@endphp
@extends('layouts.dashboard')
@section('dashboard')
    <div class="p-6 space-y-6">
        <p class="text-sm text-gray-600 mb-2">
            Reprot Transaction:
            @if (!empty($data['startDate']) || !empty($data['endDate']))
                {{ Carbon::parse($data['startDate'])->isoFormat('DD MMM YYYY') }}
                s/d
                {{ Carbon::parse($data['endDate'])->isoFormat('DD MMM YYYY') }}
            @endif
        </p>
        <p class="text-lg font-semibold">
            Total Rp. {{ number_format($total, 0, ',', '.') }}
        </p>

        <div class="flex justify-between items-center mb-4">
            <form id="filterForm" method="GET" action="{{ route('transactions.index') }}" class="flex gap-2 w-full">
                <!-- Dropdown Per Page -->
                <select name="perPage" class="rounded-lg border border-gray-300 text-sm p-2">
                    <option value="10" {{ $data['perPage'] == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $data['perPage'] == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $data['perPage'] == 50 ? 'selected' : '' }}>50</option>
                </select>

                <!-- Tanggal Mulai -->
                <div class="flex items-center gap-2">
                    <label for="startDate" class="text-sm text-gray-600">Dari</label>
                    <input type="date" id="startDate" name="startDate" value="{{ $data['startDate'] ?? '' }}"
                        class="rounded-lg border border-gray-300 text-sm p-2">
                </div>

                <!-- Tanggal Selesai -->
                <div class="flex items-center gap-2">
                    <label for="endDate" class="text-sm text-gray-600">Sampai</label>
                    <input type="date" id="endDate" name="endDate" value="{{ $data['endDate'] ?? '' }}"
                        class="rounded-lg border border-gray-300 text-sm p-2">
                </div>

                <!-- Search Box -->
                <input type="text" name="search" placeholder="Search users..." value="{{ $data['search'] ?? '' }}"
                    class="rounded-lg border border-gray-300 text-sm p-2 w-full max-w-xs ml-auto">

                <!-- Tombol Export -->
                <button type="submit" name="action" value="export"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                    Export
                </button>

                <!-- Tambah User -->
                <a href="{{ route('transactions.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Add New Transaction</a>
            </form>
        </div>
        {{-- Table Card --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="bg-blue-100">
                        <th scope="col" class="px-6 py-3">User</th>
                        <th scope="col" class="px-6 py-3">Package Name</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">status</th>
                        <th scope="col" class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactionUserPackages as $transactionUserPackage)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $transactionUserPackage->user_name }}
                            </th>
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $transactionUserPackage->package_name }}
                            </th>
                            <td class="px-6 py-4">
                                Rp. {{ number_format($transactionUserPackage->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @switch($transactionUserPackage->status)
                                    @case('active')
                                        <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                            {{ ucfirst($transactionUserPackage->status) }}
                                        </span>
                                    @break

                                    @case('request')
                                        <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                            {{ ucfirst($transactionUserPackage->status) }}
                                        </span>
                                    @break

                                    @case('cancel')
                                        <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                            {{ ucfirst($transactionUserPackage->status) }}
                                        </span>
                                    @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 items-center">
                                <form action="{{ route('transactions.edit', $transactionUserPackage->id) }}" method="GET"
                                    class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $transactionUserPackage->id }}">
                                    <button type="submit"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        Edit
                                    </button>
                                </form>
                                <form action="{{ route('transactions.destroy', $transactionUserPackage->id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $transactionUserPackage->id }}">
                                    <button type="submit"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                        onclick="return confirm('Are you sure you want to delete this package?')">
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No packages found</td>
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
            const startDateInput = form.querySelector('input[name="startDate"]');
            const endDateInput = form.querySelector('input[name="endDate"]');

            let typingTimer;

            // Auto-submit saat ganti jumlah per halaman
            perPageSelect.addEventListener('change', function() {
                form.submit();
            });
            startDateInput.addEventListener('change', function() {
                form.submit();
            });
            endDateInput.addEventListener('change', function() {
                form.submit();
            });

            // Auto-submit search setelah berhenti mengetik
            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => form.submit(), 500);
            });
        });
    </script>
