<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangModel;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::all();
    }

    public function store(Request $request)
    {
        $data = $request->all();

        //tidak memakai hashName()
        // Cek apakah ada file image yang di-upload
        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //     // Simpan file ke storage/public/images
        //     $path = $request->file('image')->store('images', 'public');
        //     // Simpan path ke dalam field image
        //     $data['image'] = $path;
        // }

        //memakai hashName()
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');

            // Melakukan Hash Name pada file
            $filename = $file->hashName();

            $file->storeAs('images', $filename, 'public');

            $data['image'] = 'images/' . $filename;
        }

        $barang = BarangModel::create($data);

        return response()->json($barang, 201);
    }

    public function show(BarangModel $barang)
    {
        return BarangModel::find($barang);
    }

    public function update(Request $request, BarangModel $barang)
    {
        $barang->update($request->all());
        return BarangModel::find($barang);
    }

    public function destroy(BarangModel $barang)
    {
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data terhapus'
        ]);
    }
}
