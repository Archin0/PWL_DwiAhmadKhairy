<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use App\Models\Akses;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
    public function index()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $breadcrumb = (object) [
                'title' => 'Daftar Kategori',
                'list' => ['Home', 'Kategori Barang']
            ];

            $activeMenu = 'kategori'; // set menu yang sedang aktif

            return view('kategori.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
        } else {
            return back();
        }
    }

    public function list()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $kategori = Kategori::select('id_kategori', 'kode_kategori', 'nama_kategori', 'foto_kategori', 'deskripsi');

            return DataTables::of($kategori)
                ->make(true);
        } else {
            return back();
        }
    }

    public function create_ajax()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            return view('kategori.create_ajax');
        } else {
            return back();
        }
    }

    public function store_ajax(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kode_kategori' => 'required|string|max:10|unique:kategori,kode_kategori',
                    'nama_kategori' => 'required|string|max:100',
                    'deskripsi' => 'required|string|max:255',
                    'foto_kategori' => 'required|image|mimes:png|max:2048'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors()
                    ]);
                }

                try {
                    // Handle file upload
                    if ($request->hasFile('foto_kategori')) {
                        $file = $request->file('foto_kategori');
                        $filename = time() . '_' . $file->getClientOriginalName();

                        // Store file in storage/app/public/car/kategori
                        $path = $file->storeAs('kategori', $filename, 'public');

                        // Create new kategori with file path
                        Kategori::create([
                            'kode_kategori' => $request->kode_kategori,
                            'nama_kategori' => $request->nama_kategori,
                            'deskripsi' => $request->deskripsi,
                            'foto_kategori' => $filename // Store only the filename
                        ]);

                        return response()->json([
                            'status' => true,
                            'message' => 'Data kategori berhasil disimpan'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'File foto kategori tidak ditemukan'
                        ]);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                    ]);
                }
            }

            return redirect('/');
        } else {
            return back();
        }
    }

    public function edit_ajax(string $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $kategori = Kategori::find($id);
            return view('kategori.edit_ajax', ['kategori' => $kategori]);
        } else {
            return back();
        }
    }

    public function update_ajax(Request $request, string $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kode_kategori' => 'required|string|max:10|unique:kategori,kode_kategori,' . $id . ',id_kategori',
                    'nama_kategori' => 'required|string|max:100',
                    'deskripsi' => 'required|string|max:255',
                    'foto_kategori' => 'sometimes|image|mimes:png|max:2048' // 'sometimes' makes it optional
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors()
                    ]);
                }

                try {
                    $kategori = Kategori::findOrFail($id);
                    $updateData = [
                        'kode_kategori' => $request->kode_kategori,
                        'nama_kategori' => $request->nama_kategori,
                        'deskripsi' => $request->deskripsi
                    ];

                    // Handle file upload if new photo is provided
                    if ($request->hasFile('foto_kategori')) {
                        // Delete old photo if exists
                        if ($kategori->foto_kategori) {
                            Storage::disk('public')->delete('kategori/' . $kategori->foto_kategori);
                        }

                        // Store new photo
                        $file = $request->file('foto_kategori');
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('kategori', $filename, 'public');
                        $updateData['foto_kategori'] = $filename;
                    }

                    $kategori->update($updateData);

                    return response()->json([
                        'status' => true,
                        'message' => 'Data kategori berhasil diubah'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                    ]);
                }
            }

            return redirect('/');
        } else {
            return back();
        }
    }

    public function confirm_ajax(string $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $kategori = Kategori::find($id);

            return view('kategori.confirm_ajax', ['kategori' => $kategori]);
        } else {
            return back();
        }
    }

    public function delete_ajax(Request $request, $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                try {
                    $kategori = Kategori::find($id);
                    if ($kategori) {
                        if ($kategori->foto_kategori) {
                            Storage::disk('public')->delete($kategori->foto_kategori);
                        }
                        $kategori->delete();
                        return response()->json([
                            'status' => true,
                            'message' => 'Data berhasil dihapus'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Data tidak ditemukan'
                        ]);
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                    ]);
                }
            }

            return redirect('/');
        } else {
            return back();
        }
    }

    public function export_pdf()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->laporan == 1) {
            $kategori = Kategori::select(
                'kode_kategori',
                'nama_kategori',
                'deskripsi'
            )
                ->orderBy('id_kategori')
                ->get();

            // Tambahkan GMT+7 ke timestamp
            $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H-i-s');

            // use Barryvdh\DomPDF\Facade\Pdf;
            $pdf = PDF::loadView('laporan.exportpdf_kategori', ['kategori' => $kategori, 'timestamp' => $timestamp]);
            $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
            $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
            // $pdf->render(); // render pdf

            // Return the PDF with headers that will open in new tab
            return $pdf->stream('Data Kategori Barang ' . date('Y-m-d H-i-s') . '.pdf', [
                'Attachment' => false // This will make the PDF open in new tab instead of downloading
            ]);
        }
    }
}
