<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Akses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{

    public function index()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $activeMenu = 'barang';
            $breadcrumb = (object) [
                'title' => 'Data Barang',
                'list' => ['Home', 'Barang']
            ];

            $kategori = Kategori::select('id_kategori', 'nama_kategori')->get();
            return view('barang.index', [
                'activeMenu' => $activeMenu,
                'breadcrumb' => $breadcrumb,
                'kategori' => $kategori
            ]);
        } else {
            return back();
        }
    }

    public function list(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $barang = Barang::select(
                'id_barang',
                'kode_barang',
                'nama_barang',
                'harga',
                'stok',
                'foto_barang',
                'id_kategori',
                'id_supplier'
            )->with('kategori', 'supplier');

            $id_kategori = $request->input('filter_kategori');
            if (!empty($id_kategori)) {
                $barang->where('id_kategori', $id_kategori);
            }

            // Filter berdasarkan search (nama atau kode barang) jika ada
            if ($request->filled('filter_search')) {
                $searchTerm = '%' . $request->filter_search . '%';

                $barang->where(function ($q) use ($searchTerm) {
                    $q->where('nama_barang', 'LIKE', $searchTerm)
                        ->orWhere('kode_barang', 'LIKE', $searchTerm);
                });
            }

            return DataTables::of($barang)
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
            $kategori = Kategori::select('id_kategori', 'nama_kategori')->get();
            $supplier = Supplier::select('id_supplier', 'nama_supplier')->get();
            return view('barang.create_ajax')->with([
                'kategori' => $kategori,
                'supplier' => $supplier
            ]);
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
                    'id_kategori' => ['required', 'integer'],
                    'id_supplier' => ['required', 'integer'],
                    'kode_barang' => [
                        'required',
                        'min:3',
                        'max:20',
                        'unique:barang,kode_barang'
                    ],
                    'nama_barang' => ['required', 'string', 'max:100'],
                    'harga' => ['required', 'numeric', 'min:1'],
                    'stok' => ['required', 'integer', 'min:0'],
                    'foto_barang' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,avif,webp', 'max:2048']
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors()
                    ], 422);
                }

                $data = $request->except('foto_barang');

                // Handle file upload
                if ($request->hasFile('foto_barang')) {
                    $file = $request->file('foto_barang');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('barang', $fileName, 'public');
                    $data['foto_barang'] = $fileName;
                }

                Barang::create($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan'
                ]);
            }
            return redirect('/');
        } else {
            return back();
        }
    }

    public function edit_ajax($id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $barang = Barang::find($id);
            $kategori = Kategori::select('id_kategori', 'nama_kategori')->get();
            $supplier = Supplier::select('id_supplier', 'nama_supplier')->get();
            return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori, 'supplier' => $supplier]);
        } else {
            return back();
        }
    }

    public function update_ajax(Request $request, $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'id_kategori' => ['required', 'integer', 'exists:kategori,id_kategori'],
                    'id_supplier' => ['required', 'integer', 'exists:supplier,id_supplier'],
                    'kode_barang' => [
                        'required',
                        'string',
                        'max:20',
                        'unique:barang,kode_barang,' . $id . ',id_barang'
                    ],
                    'nama_barang' => ['required', 'string', 'max:100'],
                    'harga' => ['required', 'numeric', 'min:1'],
                    'stok' => ['required', 'integer', 'min:0'],
                    'foto_barang' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,avif', 'max:2048']
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal',
                        'msgField' => $validator->errors()
                    ], 422);
                }

                $barang = Barang::findOrFail($id);
                $updateData = $request->except('foto_barang');

                // Handle file upload
                if ($request->hasFile('foto_barang')) {
                    // Delete old photo if exists
                    if ($barang->foto_barang && Storage::disk('public')->exists('barang/' . $barang->foto_barang)) {
                        Storage::disk('public')->delete('barang/' . $barang->foto_barang);
                    }

                    // Store new photo
                    $file = $request->file('foto_barang');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('barang', $fileName, 'public');
                    $updateData['foto_barang'] = $fileName;
                }

                $barang->update($updateData);

                return response()->json([
                    'status' => true,
                    'message' => 'Data barang berhasil diubah'
                ]);
            }
            return redirect('/');
        } else {
            return back();
        }
    }

    public function confirm_ajax($id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $barang = Barang::find($id);
            return view('barang.confirm_ajax', ['barang' => $barang]);
        } else {
            return back();
        }
    }

    public function delete_ajax(Request $request, $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            if ($request->ajax() || $request->wantsJson()) {
                $barang = Barang::find($id);
                if ($barang) { // jika sudah ditemuikan 
                    $barang->delete(); // barang di hapus 
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
            }
            return redirect('/');
        } else {
            return back();
        }
    }

    public function import()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            return view('barang.import');
        } else {
            return back();
        }
    }

    public function import_ajax(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    // validasi file harus xls atau xlsx, max 1MB 
                    'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors()
                    ]);
                }

                $file = $request->file('file_barang');  // ambil file dari request 

                $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
                $reader->setReadDataOnly(true);             // hanya membaca data 
                $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
                $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 

                $data = $sheet->toArray(null, false, true, true);   // ambil data excel 

                $errors = [];
                $insert = [];
                if (count($data) > 1) { // jika data lebih dari 1 baris 
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // baris ke 1 adalah header, maka lewati 
                            $kategoriId = $value['A'];
                            $supplierId = $value['B'];
                            // Cek apakah id_kategori ada di tabel kategori
                            if (!Kategori::where('id_kategori', $kategoriId)->exists()) {
                                $errors["baris_$baris"] = "Kategori dengan ID {$kategoriId} tidak terdaftar.";
                            }
                            // Cek apakah id_supplier ada di tabel kategori
                            if (!Supplier::where('id_supplier', $supplierId)->exists()) {
                                $errors["baris_$baris"] = "Supplier dengan ID {$supplierId} tidak terdaftar.";
                            }
                        }
                    }

                    // Jika ada error validasi kategori, kembalikan response error
                    if (count($errors) > 0) {
                        return response()->json([
                            'status'   => false,
                            'message'  => 'Validasi kategori gagal',
                            'msgField' => $errors
                        ]);
                    }

                    // Jika semua kategori valid, buat array data untuk di-insert
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // Lewati header
                            $insert[] = [
                                'id_kategori' => $value['A'],
                                'id_supplier' => $value['B'],
                                'kode_barang' => $value['C'],
                                'nama_barang' => $value['D'],
                                'stok' => $value['E'],
                                'harga' => $value['F'],
                                'created_at' => now(),
                            ];
                        }
                    }

                    if (count($insert) > 0) {
                        // insert data ke database, jika data sudah ada, maka diabaikan 
                        Barang::insertOrIgnore($insert);
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport'
                    ]);
                }
            }
            return redirect('/');
        } else {
            return back();
        }
    }

    public function export_excel()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            //ambil data barang yang akan di export
            $barang = Barang::select('id_kategori', 'id_supplier', 'kode_barang', 'nama_barang', 'stok', 'harga')
                ->orderBy('id_kategori')
                ->with('kategori', 'supplier')
                ->get();

            //load library excel
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet(); //ambil sheet yang aktif

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Kode Barang');
            $sheet->setCellValue('C1', 'Nama Barang');
            $sheet->setCellValue('D1', 'Harga Jual');
            $sheet->setCellValue('E1', 'Stok');
            $sheet->setCellValue('F1', 'Kategori');
            $sheet->setCellValue('G1', 'Supplier');

            $sheet->getStyle('A1:G1')->getFont()->setBold(true); //bold header

            $no = 1;
            $baris = 2;
            foreach ($barang as $key => $value) {
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $value->kode_barang);
                $sheet->setCellValue('C' . $baris, $value->nama_barang);
                $sheet->setCellValue('D' . $baris, $value->harga);
                $sheet->setCellValue('E' . $baris, $value->stok);
                $sheet->setCellValue('F' . $baris, $value->kategori->nama_kategori); //ambil nama kategori
                $sheet->setCellValue('G' . $baris, $value->supplier->nama_supplier); //ambil nama supplier
                $baris++;
                $no++;
            }

            foreach (range('A', 'G') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            } //atur lebar kolom

            $sheet->setTitle('Data Barang'); //set title sheet

            $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H-i-s');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'Data Barang ' . $timestamp . '.xlsx'; //nama file excel

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0'); // For IE to not cache the response
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . $timestamp . ' GMT+7');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $writer->save('php://output');
            exit;
        } else {
            return back();
        }
    }

    public function export_pdf()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->laporan == 1) {
            $barang = Barang::select(
                'barang.kode_barang',
                'barang.nama_barang',
                'barang.stok',
                'barang.harga',
                'kategori.nama_kategori',
                'supplier.nama_supplier'
            )
                ->join('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori')
                ->join('supplier', 'barang.id_supplier', '=', 'supplier.id_supplier')
                ->orderBy('barang.id_barang')
                ->get();

            // Tambahkan GMT+7 ke timestamp
            $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H-i-s');

            $pdf = PDF::loadView('laporan.exportpdf_barang', ['barang' => $barang, 'timestamp' => $timestamp]);
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption("isRemoteEnabled", true);

            return $pdf->stream('Data Barang ' . $timestamp . '.pdf', [
                'Attachment' => false
            ]);
        } else {
            return back();
        }
    }
}
