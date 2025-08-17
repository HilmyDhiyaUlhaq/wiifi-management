@extends('layouts.dashboard')
@section('dashboard')
    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Users</h1>

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
                    <a href="{{ route('users.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Add New User</a>
                </form>
            </div>


            <!-- Table -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="bg-blue-100">
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Role</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $user->name }}
                                </th>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs {{ $user->role == 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs {{ $user->userWifi?->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($user->userWifi?->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('users.transactions.index', $user->id) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">Detail</a>
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                            onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filterForm');
        const searchInput = form.querySelector('input[name="search"]');
        const perPageSelect = form.querySelector('select[name="perPage"]');
        let typingTimer;

        // Auto-submit saat ganti jumlah per halaman
        perPageSelect.addEventListener('change', function () {
            form.submit();
        });

        // Auto-submit search setelah berhenti mengetik
        searchInput.addEventListener('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => form.submit(), 500);
        });
    });
</script>