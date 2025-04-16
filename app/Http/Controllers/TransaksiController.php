<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaksi::with([
            'member',
            'user',
            'penjualan.detailPenjualan.produk' // make sure your relation names match the models
        ])->get();

        return view('purchase.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchase.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk' => 'required|array',
            'produk.*' => 'integer|min:1',
            'total_bayar' => 'required|integer|min:0',
            'member' => 'nullable|string',
            'no_telepon' => 'nullabale|string',
        ]);

        $total_bayar = str_replace(".", "", $request->total_bayar);
        $total_harga = (int) $request->total_harga;

        if (array_sum($request->produk) == 0) {
            return back()->with('Error', 'Silahkan pilih produk sebelum checkout');
        }

        if ($request->member == 'member') {
            session([
                'checkout_data' => $request->produk,
                'total_harga' => $total_harga,
                'total_bayar' => $total_bayar,
                'no_hp' => $request->no_hp
            ]);

            return redirect()->route('transaction.member.checkout'); 
        }

        return $this->processTransaction($request, null);

    }

    // function untuk membawa product ke halaman checkout(form)
    public function checkout(Request $request)
    {
        $cart = session('cart', []); 
        
        $produkIds = array_keys($cart);
        $produks = Produk::whereIn('id', $produkIds)->get();

        return view('purchase.sale.checkout', compact('produks', 'cart'));
    }

    public function cart(Request $request)
    {
        $cart = array_filter($request->produk, function ($qty) {
            return $qty > 0; 
        });

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Pilih minimal satu produk!');
        }

        session(['cart' => $cart]); 
        return redirect()->route('transaction.checkout')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function storeSession(Request $request)
    {
        $produkInput = $request->input('produk'); 
        $totalBayar = str_replace('.', '', $request->input('total_bayar'));
        $totalHarga = str_replace('.', '', $request->input('total_harga'));
        $noHp = $request->input('no_hp');

        session([
            'checkout_data' => [
                'produk' => $produkInput,
                'total_bayar' => (int) $totalBayar,
                'total_harga' => (int) $totalHarga,
                'no_hp' => $noHp,
            ]
        ]);


        return response()->json(['status' => 'success']);
    }


    // Menampilkan halaman checkout member 
    public function checkoutMember()
    {
        $checkoutData = session('checkout_data');

        if (!$checkoutData) {
            return redirect()->route('transaction.checkout')->with('error', 'Data checkout tidak ditemukan.');
        }

        return view('purchase.sale.co_member', [
            'checkoutData' => $checkoutData,
            'totalBayar' => $checkoutData['total_bayar'],
            'point' => floor($checkoutData['total_harga'] * 0.10), // contoh: 1 poin per 1000
        ]);
    }

    // Function untuk mengirim data checkout ke database (non member)
    private function processTransaction(Request $request, $id_member = null) {
        $penjualan = Penjualan::create([
            'total_harga' => $request->total_harga,
            'tanggal_penjualan' => now(),
            'total_produk' => array_sum($request->produk),
        ]);

        // Simpan detail pembelian ke tabel DetailPenjualan
        foreach ($request->produk as $id_produk => $qty) {
            if ($qty > 0) {
                $produk = Produk::find($id_produk);
                if ($produk && $produk->stock < $qty) {
                    return back()->with('error', 'Stok produk ' . $produk->nama_produk . ' tidak mencukupi.');
                }
                DetailPenjualan::create([
                        'id_penjualan' => $penjualan->id,
                        'id_produk' => $id_produk,
                        'quantity' => $qty,
                        'subtotal' => $produk->harga * $qty
                    ]);

                    // Kurangi stok produk
                    $produk->stock -= $qty;
                    $produk->save();

            }
        }

        $kembalian = max(0, $request->total_bayar - $request->total_harga);

        // Simpan transaksi ke tabel Transaksi
        Transaksi::create([
            'kembalian' => $kembalian,
            'total_bayar' => $request->total_bayar,
            'sub_total' => $request->total_harga,
            'id_member' => $id_member,
            'id_users' => Auth::id(),
            'id_penjualan' => $penjualan->id
        ]);

        session()->forget(['checkout_data', 'total_harga', 'total_bayar', 'no_hp']);

        $transaksi = Transaksi::where('id_penjualan', $penjualan->id)->first();

        return redirect()->route('transaction.checkout.success', $transaksi->id)->with('success', 'Transaksi berhasil!');
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
}
