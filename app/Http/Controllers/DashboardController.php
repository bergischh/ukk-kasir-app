<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\DetailPenjualan;


use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $salesCountToday = Transaksi::whereDate('created_at', Carbon::today())->count();

        $latestTransaksi = Transaksi::latest()->first();

        $dateRange = now()->subDays(6)->toPeriod(now());
        $dates = [];
        $salesCountChart = [];

        foreach ($dateRange as $date) {
            $formattedDate = $date->format('d M');
            $dates[] = $formattedDate;

            $count = Transaksi::whereDate('created_at', $date)->count();
            $salesCountChart[] = $count;
        }

        $produkTerjual = DetailPenjualan::select('id_produk', DB::raw('SUM(quantity) as total'))
            ->groupBy('id_produk')
            ->with('produk')
            ->get();

        $productNames = [];
        $productTotals = [];

        foreach ($produkTerjual as $item) {
            if ($item->produk) {
                $productNames[] = $item->produk->nama_produk;
                $productTotals[] = $item->total;
            }
        }

        return view('dashboard', compact(
            'salesCountToday',
            'latestTransaksi',
            'dates',
            'salesCountChart',
            'productNames',
            'productTotals'
        ));
    }
}
