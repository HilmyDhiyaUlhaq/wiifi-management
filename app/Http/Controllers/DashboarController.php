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
        $totalClasses = TransactionUserPackage::leftJoin('users', 'transactions_users_packages.user_id', '=', 'users.id')->where('users.deleted_at', null)->distinct('package_id')->count('package_id');

        // Hitung total nominal transaksi WiFi (type 'package')
        $totalWifiTransactions = TransactionUserPackage::leftJoin('users', 'transactions_users_packages.user_id', '=', 'users.id')->where('users.deleted_at', null)->where('type', 'package')->sum('price') ?? 0;

        // Hitung total nominal transaksi Kelas (type 'class')
        $totalClassTransactions = TransactionUserPackage::leftJoin('users', 'transactions_users_packages.user_id', '=', 'users.id')->where('users.deleted_at', null)->where('type', 'class')->sum('price') ?? 0;

        // Hitung total transaksi (jumlah record)
        $totalTransactions = TransactionUserPackage::leftJoin('users', 'transactions_users_packages.user_id', '=', 'users.id')->where('users.deleted_at', null)->count();

        // Hitung total revenue (semua transaksi)
        $totalRevenue = TransactionUserPackage::leftJoin('users', 'transactions_users_packages.user_id', '=', 'users.id')->where('users.deleted_at', null)->sum('price') ?? 0;

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
