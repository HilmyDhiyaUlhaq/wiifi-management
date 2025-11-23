@extends('layouts.dashboard')
@section('dashboard')
    <div class="p-6 space-y-8">
        {{-- Header Section --}}
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-0 -mb-4 leading-loose">Kelola WiFi Manager dengan mudah</p>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="dashboardCards">
            {{-- Total Users Card --}}
            <a href="{{ route('users.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Pengguna</p>
                        <p class="text-3xl font-bold text-gray-900 mt-3" data-stat="totalUsers">{{ $totalUsers ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 10a3 3 0 11-6 0 3 3 0 016 0zM12.93 15.93A9.001 9.001 0 1020 9a.75.75 0 00-1.5 0A7.5 7.5 0 1112.93 15.93z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Total Packages Card --}}
            <a href="{{ route('packages.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Paket</p>
                        <p class="text-3xl font-bold text-gray-900 mt-3" data-stat="totalPackages">{{ $totalPackages ?? 0 }}
                        </p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Total Classes Card --}}
            <a href="{{ route('transactions-class.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Kelas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-3" data-stat="totalClasses">{{ $totalClasses ?? 0 }}
                        </p>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5M6.5 6.5h7M6.5 10h7M6.5 13.5h4">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Total WiFi Transactions Card --}}
            <a href="{{ route('transactions.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Transaksi WiFi</p>
                        <p class="text-2xl font-bold text-gray-900 mt-3" data-stat="totalWifiTransactions"
                            data-format="currency">Rp {{ number_format($totalWifiTransactions ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Total Class Transactions Card --}}
            <a href="{{ route('transactions-class.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Transaksi Kelas</p>
                        <p class="text-2xl font-bold text-gray-900 mt-3" data-stat="totalClassTransactions"
                            data-format="currency">Rp {{ number_format($totalClassTransactions ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-3">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Total Revenue Card --}}
            <a href="{{ route('transactions.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-900 mt-3" data-stat="totalRevenue" data-format="currency">Rp
                            {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-3">
                        <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M8.16 5.314l4.897-1.596A.5.5 0 0114 4.5v.006c0 .263-.215.474-.48.427L9.516 4.1A7 7 0 004 10.5H.5a.5.5 0 010-1h1.05A8.05 8.05 0 018.16 5.314zm4.823 7.18l-4.897 1.596A.5.5 0 016 14.5v-.006c0-.263.215-.474.48-.427l4.401 1.432A7 7 0 0016 9.5h3.5a.5.5 0 010 1h-1.05a8.05 8.05 0 01-4.361 2.994z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script>
        // Fungsi untuk refresh data dashboard
        async function refreshDashboardData() {
            try {
                const response = await fetch(window.location.href);
                const html = await response.text();
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');

                // Update setiap card dengan data baru
                const stats = ['totalUsers', 'totalPackages', 'totalClasses', 'totalWifiTransactions',
                    'totalClassTransactions', 'totalRevenue'
                ];

                stats.forEach(stat => {
                    const newValue = newDoc.querySelector(`[data-stat="${stat}"]`);
                    const currentValue = document.querySelector(`[data-stat="${stat}"]`);

                    if (newValue && currentValue) {
                        const newText = newValue.textContent.trim();
                        const currentText = currentValue.textContent.trim();

                        if (newText !== currentText) {
                            currentValue.textContent = newText;
                            currentValue.parentElement.parentElement.parentElement.style.opacity = '0.6';
                            setTimeout(() => {
                                currentValue.parentElement.parentElement.parentElement.style.opacity =
                                    '1';
                            }, 300);
                        }
                    }
                });
            } catch (error) {
                console.error('Error refreshing dashboard:', error);
            }
        }

        // Auto refresh setiap 5 detik
        setInterval(refreshDashboardData, 5000);

        // Manual refresh saat tab menjadi fokus
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                refreshDashboardData();
            }
        });
    </script>
@endsection
