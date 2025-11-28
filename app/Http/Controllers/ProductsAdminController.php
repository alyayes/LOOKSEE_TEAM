<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductsAdminController extends Controller
{
    public function index()
    {
        $products = Produk::all();
        return view('admin.produk.index', compact('products'));
    }

    public function add()
    {
        return view('admin.produk.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable',
            'gambar_produk' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('gambar_produk');
        $folder = public_path('assets/images/produk-looksee');

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $namaFile = time() . '.' . $file->extension();
        $file->move($folder, $namaFile);

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'gambar_produk' => $namaFile,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = Produk::findOrFail($id);

        // Jika upload gambar baru
        if ($request->hasFile('gambar_produk')) {
            $oldPath = public_path('assets/images/produk-looksee/' . $product->gambar_produk);

            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $file = $request->file('gambar_produk');
            $namaFile = time() . '.' . $file->extension();
            $file->move(public_path('assets/images/produk-looksee'), $namaFile);

            // Simpan nama file ke model
            $product->gambar_produk = $namaFile;
        }

        // Update data lain
        $product->nama_produk = $request->nama_produk;
        $product->kategori = $request->kategori;
        $product->deskripsi = $request->deskripsi;
        $product->harga = (float)$request->harga;
        $product->save();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $product = Produk::findOrFail($id);

        $path = public_path('assets/images/produk-looksee/' . $product->gambar_produk);
        if (File::exists($path)) {
            File::delete($path);
        }

        $product->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
