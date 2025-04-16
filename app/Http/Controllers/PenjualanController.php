<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Produk;


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

    public function index() {
        $products = Produk::all();

        return view('purchase.index', compact('products'));
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
}
