<?php

namespace App\Http\Controllers;

use App\Models\Users\User;
use App\Models\Package;
use App\Models\Transaction\TransactionUserPackage;
use Illuminate\Http\Request;

class DashboarController extends Controller
{
    public function __invoke()
    {
        // Hitung total pengguna
        $totalUsers = User::count();

        // Hitung total paket
        $totalPackages = Package::count();

        // Hitung total kelas (paket unik dari transaksi)
        $totalClasses = TransactionUserPackage::distinct('package_id')->count('package_id');

        // Hitung total nominal transaksi WiFi (type 'package')
        $totalWifiTransactions = TransactionUserPackage::where('type', 'package')->sum('price') ?? 0;

        // Hitung total nominal transaksi Kelas (type 'class')
        $totalClassTransactions = TransactionUserPackage::where('type', 'class')->sum('price') ?? 0;

        // Hitung total transaksi (jumlah record)
        $totalTransactions = TransactionUserPackage::count();

        // Hitung total revenue (semua transaksi)
        $totalRevenue = TransactionUserPackage::sum('price') ?? 0;

        return view('pages.dashboards.index', [
            'totalUsers' => $totalUsers,
            'totalPackages' => $totalPackages,
            'totalClasses' => $totalClasses,
            'totalWifiTransactions' => $totalWifiTransactions,
            'totalClassTransactions' => $totalClassTransactions,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
        ]);
    }
}
