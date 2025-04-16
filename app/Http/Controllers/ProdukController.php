<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Produk::all();
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required',
            'stock' => 'required',
            'gambar_produk' => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);

        $productData = [
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stock' => $request->stock,
        ];
        
        if ($request->file('gambar_produk')->isValid()) {
            $file = $request->file('gambar_produk');
            $path = $file->store('gambar_product', 'public');

            $productData ['gambar_produk'] = $path;
        }

        Produk::create($productData);


        return redirect()->route('product.index')->with('Success', 'Berhasil menambah data!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $products = Produk::find($id);

        return view('product.edit', compact('products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required',
            'stock' => 'required',
            'gambar_produk' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Ambil data produk lama
        $produk = Produk::findOrFail($id);

        // Siapkan data untuk update
        $dataUpdate = [
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stock' => $request->stock,
        ];

        // Jika ada file gambar yang diunggah, simpan dan update gambar
        if ($request->hasFile('gambar_produk')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar_produk) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }

            // Simpan gambar baru
            $file = $request->file('gambar_produk');
            $path = $file->store('gambar_product', 'public');
            $dataUpdate['gambar_produk'] = $path;
        }

        // Update produk dengan data baru
        $produk->update($dataUpdate);

        return redirect()->route('product.index')->with('Success', 'Berhasil memperbarui data!');
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0', 
        ]);

        $product = Produk::findOrFail($id);
        $product->update([
            'stock' => $request->stock,
        ]);

        return redirect()->route('product.index')->with('success', 'Stock berhasil diperbarui!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Produk::where('id', $id)->delete();

        return redirect()->back()->with('Success', 'Berhasil menghapus data!');
    }
}
