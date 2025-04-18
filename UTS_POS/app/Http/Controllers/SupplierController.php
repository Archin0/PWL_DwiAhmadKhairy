<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Akses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
{
    public function index()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $breadcrumb = (object) [
                'title' => 'Daftar Supplier',
                'list' => ['Dashboard', 'Daftar Supplier']
            ];

            $activeMenu = 'supplier'; //set menu yang sedang aktif

            return view('supplier.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
        } else {
            return back();
        }
    }

    public function list(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            if ($request->ajax()) {
                $data = Supplier::select('id_supplier', 'kode_supplier', 'nama_supplier', 'alamat');

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('aksi', function ($row) {
                        $btn = '<button onclick="modalAction(\'' . url('/supplier/' . $row->id_supplier .
                            '/edit_ajax') . '\')" class="btn btn-edit btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                        $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $row->id_supplier .
                            '/delete_ajax') . '\')"  class="btn btn-delete btn-danger btn-sm"><i class="fas fa-trash"></i></button> ';

                        return $btn;
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
            }
        }
    }

    public function create_ajax()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            return view('supplier.create_ajax');
        }
    }

    public function store_ajax(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            // cek apakah request berupa ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kode_supplier' => 'required|string|min:5|unique:supplier,kode_supplier',
                    'nama_supplier' => 'required|string|max:100',
                    'alamat' => 'required|string|min:3|max:255'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false, // response status, false: error/gagal, true: berhasil
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors() // pesan error validasi
                    ]);
                }

                $data = $request->all();

                // Create data
                Supplier::create($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil disimpan'
                ]);
            }

            return redirect('/');
        }
    }

    public function edit_ajax(string $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $supplier = Supplier::find($id);

            return view('supplier.edit_ajax', ['supplier' => $supplier]);
        }
    }

    public function update_ajax(Request $request, $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kode_supplier' => 'required|string|min:5|unique:supplier,kode_supplier, ' . $id . ',id_supplier',
                    'nama_supplier' => 'required|string|max:100',
                    'alamat' => 'required|string|min:3|max:255'
                ];

                // use Illuminate\Support\Facades\Validator;
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false, // respon json, true: berhasil, false: gagal
                        'message' => 'Validasi gagal.',
                        'msgField' => $validator->errors() // menunjukkan field mana yang error
                    ]);
                }
                $check = Supplier::find($id);
                if ($check) {
                    $check->update($request->all());
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diupdate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
            }
            return redirect('/');
        }
    }

    public function confirm_ajax(string $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $supplier = Supplier::find($id);

            return view('supplier.confirm_ajax', ['supplier' => $supplier]);
        }
    }

    public function delete_ajax(Request $request, $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $supplier = Supplier::find($id);

                if ($supplier) {
                    $supplier->delete();
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
        }
    }

    public function import()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            return view('supplier.import');
        }
    }

    public function import_ajax(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            if ($request->ajax() || $request->wantsJson()) {

                $rules = [
                    // Validasi file harus xlsx, maksimal 1MB
                    'file_supplier' => ['required', 'mimes:xlsx', 'max:1024']
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status'   => false,
                        'message'  => 'Validasi Gagal',
                        'msgField' => $validator->errors()
                    ]);
                }

                // Ambil file dari request
                $file = $request->file('file_supplier');

                // Membuat reader untuk file excel dengan format Xlsx
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true); // Hanya membaca data saja

                // Load file excel
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

                // Ambil data excel sebagai array
                $data = $sheet->toArray(null, false, true, true);
                $insert = [];
                // $errors = [];

                // Pastikan data memiliki lebih dari 1 baris (header + data)
                if (count($data) > 1) {
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // Baris pertama adalah header, jadi lewati
                            $insert[] = [
                                'kode_supplier' => $value['A'],
                                'nama_supplier' => $value['B'],
                                'alamat' => $value['C'],
                                'created_at'  => now(),
                            ];
                        }
                    }

                    if (count($insert) > 0) {
                        // Insert data ke database, jika data sudah ada, maka diabaikan
                        Supplier::insertOrIgnore($insert);
                    }

                    return response()->json([
                        'status'  => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Tidak ada data yang diimport'
                    ]);
                }
            }

            return redirect('/');
        }
    }

    public function export_excel()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            //ambil data barang yang akan di export
            $supplier = Supplier::select('id_supplier', 'kode_supplier', 'nama_supplier', 'alamat')
                ->orderBy('id_supplier')
                ->get();

            //load library excel
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet(); //ambil sheet yang aktif

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Kode Supplier');
            $sheet->setCellValue('C1', 'Nama Supplier');
            $sheet->setCellValue('D1', 'Alamat');

            $sheet->getStyle('A1:D1')->getFont()->setBold(true); //bold header

            $no = 1;
            $baris = 2;
            foreach ($supplier as $key => $value) {
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $value->kode_supplier);
                $sheet->setCellValue('C' . $baris, $value->nama_supplier);
                $sheet->setCellValue('D' . $baris, $value->alamat);
                $baris++;
                $no++;
            }

            foreach (range('A', 'D') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            } //atur lebar kolom

            $sheet->setTitle('Data Supplier'); //set title sheet

            $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H-i-s');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'Data Supplier ' . $timestamp . '.xlsx'; //nama file excel

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
            $supplier = Supplier::select(
                'kode_supplier',
                'nama_supplier',
                'alamat'
            )
                ->orderBy('id_supplier')
                ->get();

            // Tambahkan GMT+7 ke timestamp
            $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H-i-s');

            $pdf = PDF::loadView('laporan.exportpdf_supplier', ['supplier' => $supplier, 'timestamp' => $timestamp]);
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption("isRemoteEnabled", true);

            return $pdf->stream('Data Supplier ' . $timestamp . '.pdf', [
                'Attachment' => false
            ]);
        } else {
            return back();
        }
    }
}
