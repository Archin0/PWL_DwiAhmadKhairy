<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supply;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $activeMenu = 'dashboard';
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Dashboard']
        ];

        // Total Barang
        $totalBarang = Barang::count();

        // Total Pemasukan (all time)
        $totalPemasukan = Transaksi::sum('total_harga');

        // Pemasukan Harian
        // $pemasukanHarian = Transaksi::whereDate('created_at', today())->sum('total_harga');

        // Total Modal (sum harga_beli * jumlah dari supply)
        $totalModal = Supply::sum(DB::raw('harga_beli * jumlah'));

        // Total Laba
        $totalLaba = $totalPemasukan - $totalModal;

        // Pelanggan Harian
        $pelangganHarian = Transaksi::whereDate('created_at', today())->count();

        // Weekly Income Data (last 7 days)
        $weeklyData = [];
        $weeklyLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $weeklyLabels[] = $date->isoFormat('dddd');
            $weeklyData[] = Transaksi::whereDate('created_at', $date)->sum('total_harga');
        }

        // Monthly Income Data (last 30 days)
        $monthlyData = [];
        $monthlyLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $monthlyLabels[] = $date->format('d M');
            $monthlyData[] = Transaksi::whereDate('created_at', $date)->sum('total_harga');
        }

        // Recent Transactions
        $recentTransactions = Transaksi::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', ['activeMenu' => $activeMenu, 'breadcrumb' => $breadcrumb], compact(
            'totalBarang',
            'totalPemasukan',
            'totalLaba',
            'pelangganHarian',
            'weeklyData',
            'weeklyLabels',
            'monthlyData',
            'monthlyLabels',
            'recentTransactions'
        ));
    }
}
