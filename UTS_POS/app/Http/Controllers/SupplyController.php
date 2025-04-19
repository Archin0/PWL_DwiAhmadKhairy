<?php

namespace App\Http\Controllers;

use App\Models\Akses;
use App\Models\Barang;
use App\Models\Supply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplyController extends Controller
{
    public function index()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $activeMenu = 'supply';
            $breadcrumb = (object) [
                'title' => 'Data Supply',
                'list' => ['Home', 'Data Supply']
            ];

            $user = User::select('id_user', 'nama')->get();
            $barang = Barang::select('id_barang', 'kode_barang', 'nama_barang')->get();

            return view('supply.index', [
                'activeMenu' => $activeMenu,
                'breadcrumb' => $breadcrumb,
                'user' => $user,
                'barang' => $barang
            ]);
        } else {
            return back();
        }
    }

    public function list()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            $supplies = Supply::with(['barang', 'user'])
                ->select('supply.*');

            return DataTables::of($supplies)
                ->addColumn('aksi', function ($supply) {
                    $btn = '<button onclick="modalAction(\'' . url('/supply/' . $supply->id_supply .
                        '/edit_ajax') . '\')" class="btn btn-edit btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/supply/' . $supply->id_supply .
                        '/delete_ajax') . '\')"  class="btn btn-delete btn-danger btn-sm"><i class="fas fa-trash"></i></button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } else {
            return response()->json([], 403);
        }
    }

    public function create_ajax()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang != 1) {
            return back();
        }

        $id_barang = request('id_barang');

        if ($id_barang) {
            // Mode from barang
            $barang = Barang::findOrFail($id_barang);
            return view('supply.create_ajax', ['barang' => $barang]);
        } else {
            // Mode from supply menu
            $barangList = Barang::orderBy('kode_barang')->get();
            return view('supply.create_ajax', ['barangList' => $barangList]);
        }
    }

    public function store_ajax(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang != 1) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'id_barang' => 'required|exists:barang,id_barang',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        try {
            // Create supply
            Supply::create([
                'id_barang' => $request->id_barang,
                'id_user' => Auth::id(),
                'jumlah' => $request->jumlah,
                'harga_beli' => $request->harga_beli,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update stok barang
            $barang = Barang::find($request->id_barang);
            $barang->stok += $request->jumlah;
            $barang->save();

            return response()->json([
                'status' => true,
                'message' => 'Stok berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan stok: ' . $e->getMessage()
            ]);
        }
    }

    public function edit_ajax($id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang != 1) {
            return back();
        }

        $supply = Supply::with('barang')->find($id);
        return view('supply.edit_ajax', ['supply' => $supply]);
    }

    public function update_ajax(Request $request, $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang != 1) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $supply = Supply::find($id);
            $barang = Barang::find($supply->id_barang);

            // Hitung selisih jumlah
            $selisih = $request->jumlah - $supply->jumlah;

            // Update supply
            $supply->update([
                'jumlah' => $request->jumlah,
                'harga_beli' => $request->harga_beli
            ]);

            // Update stok barang
            $barang->stok += $selisih;
            $barang->save();

            return response()->json([
                'status' => true,
                'message' => 'Supply berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate supply: ' . $e->getMessage()
            ]);
        }
    }

    public function confirm_ajax($id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang != 1) {
            return back();
        }

        $supply = Supply::find($id);
        return view('supply.confirm_ajax', ['supply' => $supply]);
    }

    public function delete_ajax($id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang != 1) {
            return back();
        }

        try {
            $supply = Supply::find($id);
            $barang = Barang::find($supply->id_barang);

            // Kurangi stok barang
            $barang->stok -= $supply->jumlah;
            $barang->save();

            // Hapus supply
            $supply->delete();

            return response()->json([
                'status' => true,
                'message' => 'Supply berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus supply: ' . $e->getMessage()
            ]);
        }
    }

    public function import()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_barang == 1) {
            return view('supply.import');
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
                    'file_supply' => ['required', 'mimes:xlsx', 'max:1024']
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors()
                    ]);
                }

                $file = $request->file('file_supply');  // ambil file dari request 

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
                            $barangId = $value['A'];
                            // Cek apakah id_barang ada di tabel kategori
                            if (!Barang::where('id_barang', $barangId)->exists()) {
                                $errors["baris_$baris"] = "Barang dengan ID {$barangId} tidak terdaftar.";
                            }
                        }
                    }

                    // Jika ada error validasi, kembalikan response error
                    if (count($errors) > 0) {
                        return response()->json([
                            'status'   => false,
                            'message'  => 'Validasi gagal',
                            'msgField' => $errors
                        ]);
                    }

                    // Jika semua valid, buat array data untuk di-insert
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // Lewati header
                            $insert[] = [
                                'id_barang' => $value['A'],
                                'id_user' => Auth::id(),
                                'jumlah' => $value['B'],
                                'harga_beli' => $value['C'],
                                'created_at' => now(),
                            ];
                        }
                    }

                    if (count($insert) > 0) {
                        // insert data ke database, jika data sudah ada, maka diabaikan 
                        Supply::insertOrIgnore($insert);
                    }

                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // Lewati header
                            // Update stok barang
                            $barang = Barang::find($value['A']);
                            $barang->stok += $value['B'];
                            $barang->updated_at = now();
                            $barang->save();
                        }
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
            $supplies = Supply::select('id_barang', 'id_user', 'jumlah', 'harga_beli', 'created_at')
                ->orderBy('created_at')
                ->with('barang', 'user')
                ->get();

            //load library excel
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet(); //ambil sheet yang aktif

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Tanggal');
            $sheet->setCellValue('C1', 'Kode Barang');
            $sheet->setCellValue('D1', 'Nama Barang');
            $sheet->setCellValue('E1', 'Jumlah');
            $sheet->setCellValue('F1', 'Harga Beli');
            $sheet->setCellValue('G1', 'Operator');

            $sheet->getStyle('A1:G1')->getFont()->setBold(true); //bold header

            $no = 1;
            $baris = 2;
            foreach ($supplies as $key => $value) {
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $value->created_at);
                $sheet->setCellValue('C' . $baris, $value->barang->kode_barang);
                $sheet->setCellValue('D' . $baris, $value->barang->nama_barang);
                $sheet->setCellValue('E' . $baris, $value->jumlah);
                $sheet->setCellValue('F' . $baris, $value->harga_beli);
                $sheet->setCellValue('G' . $baris, $value->user->nama);
                $baris++;
                $no++;
            }

            foreach (range('A', 'G') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            } //atur lebar kolom

            $sheet->setTitle('Data Supply Barang'); //set title sheet

            $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H-i-s');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'Data Supply Barang ' . $timestamp . '.xlsx'; //nama file excel

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
            $supplies = Supply::select('id_barang', 'id_user', 'jumlah', 'harga_beli', 'created_at')
                ->orderBy('created_at')
                ->with('barang', 'user')
                ->get();

            // Tambahkan GMT+7 ke timestamp
            $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H-i-s');

            $pdf = PDF::loadView('laporan.exportpdf_supply', ['supplies' => $supplies, 'timestamp' => $timestamp]);
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption("isRemoteEnabled", true);

            return $pdf->stream('Data Supply Barang ' . $timestamp . '.pdf', [
                'Attachment' => false
            ]);
        } else {
            return back();
        }
    }
}
