<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Transaksi; // Use the correct model here

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenjualanExport;
use App\Exports\PenjualanFilteredExport;


class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function menuShow()
    {
        $produks = Produk::all();
        $penjualans = Penjualan::with('produk')->get();
        // Ambil data stok dari session, jika ada
        $cart = session()->get('cart', []);

        return view('purchase.sale.menu', compact('penjualans', 'produks', 'cart'));
    }

   // Inside PenjualanController
    public function index(Request $request)
    {
        // Get the filter values
        $tanggal = $request->input('tanggal');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // Query to filter data based on the provided filters
        $query = Transaksi::query();

        // Filter by tanggal if provided
        if ($tanggal) {
            $query->whereHas('penjualan', function ($query) use ($tanggal) {
                $query->whereDay('tanggal_penjualan', $tanggal);
            });
        }

        // Filter by bulan if provided
        if ($bulan) {
            $query->whereHas('penjualan', function ($query) use ($bulan) {
                $query->whereMonth('tanggal_penjualan', $bulan);
            });
        }

        // Filter by tahun if provided
        if ($tahun) {
            $query->whereHas('penjualan', function ($query) use ($tahun) {
                $query->whereYear('tanggal_penjualan', $tahun);
            });
        }

        // Fetch the filtered transactions
        $transactions = $query->with(['penjualan', 'member', 'user'])->get();

        // Return the view with the filtered data
        return view('purchase.index', compact('transactions'));
    }

    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export()
    {
        return Excel::download(new PenjualanExport, 'laporan-pembelian.xlsx');
    }

    
  // Inside PenjualanController

  public function exportFiltered(Request $request)
  {
      // Ambil parameter dari form filter
      $tanggal = $request->input('tanggal');
      $bulan = $request->input('bulan');
      $tahun = $request->input('tahun');
  
      // Query untuk mengambil data sesuai filter
      $query = Transaksi::query();
  
      // Menambahkan whereHas untuk mengakses penjualan.tanggal_penjualan
      if ($tanggal) {
          $query->whereHas('penjualan', function ($query) use ($tanggal) {
              $query->whereDay('tanggal_penjualan', $tanggal);
          });
      }
  
      if ($bulan) {
          $query->whereHas('penjualan', function ($query) use ($bulan) {
              $query->whereMonth('tanggal_penjualan', $bulan);
          });
      }
  
      if ($tahun) {
          $query->whereHas('penjualan', function ($query) use ($tahun) {
              $query->whereYear('tanggal_penjualan', $tahun);
          });
      }
  
      // Ambil transaksi sesuai dengan filter
      $transactions = $query->with(['penjualan', 'member', 'user'])->get();
  
      // Jika ada permintaan untuk mengunduh file Excel
      return Excel::download(new PenjualanFilteredExport($tanggal, $bulan, $tahun), 'penjualan_filtered.xlsx');
  }
  

    


    // Fungsi lainnya seperti export umum, dll.



    
}
