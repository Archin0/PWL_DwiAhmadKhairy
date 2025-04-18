<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori Barang']
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    public function list()
    {
        $kategori = Kategori::select('id_kategori', 'kode_kategori', 'nama_kategori', 'foto_kategori', 'deskripsi');

        return DataTables::of($kategori)
            ->make(true);
    }

    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request)
    {
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
    }

    public function edit_ajax(string $id)
    {
        $kategori = Kategori::find($id);
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, string $id)
    {

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
    }

    public function confirm_ajax(string $id)
    {
        $kategori = Kategori::find($id);

        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, $id)
    {
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
    }

    // public function import()
    // {
    //     return view('kategori.import');
    // }

    // public function import_ajax(Request $request)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {

    //         $rules = [
    //             // Validasi file harus xlsx, maksimal 1MB
    //             'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
    //         ];

    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status'   => false,
    //                 'message'  => 'Validasi Gagal',
    //                 'msgField' => $validator->errors()
    //             ]);
    //         }

    //         // Ambil file dari request
    //         $file = $request->file('file_kategori');

    //         // Membuat reader untuk file excel dengan format Xlsx
    //         $reader = IOFactory::createReader('Xlsx');
    //         $reader->setReadDataOnly(true); // Hanya membaca data saja

    //         // Load file excel
    //         $spreadsheet = $reader->load($file->getRealPath());
    //         $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

    //         // Ambil data excel sebagai array
    //         $data = $sheet->toArray(null, false, true, true);
    //         $insert = [];

    //         // Pastikan data memiliki lebih dari 1 baris (header + data)
    //         if (count($data) > 1) {
    //             foreach ($data as $baris => $value) {
    //                 if ($baris > 1) { // Baris pertama adalah header, jadi lewati
    //                     $insert[] = [
    //                         'kode_kategori' => $value['A'],
    //                         'nama_kategori' => $value['B'],
    //                         'deskripsi' => $value['C'],
    //                         'created_at'  => now(),
    //                     ];
    //                 }
    //             }

    //             if (count($insert) > 0) {
    //                 // Insert data ke database, jika data sudah ada, maka diabaikan
    //                 Kategori::insertOrIgnore($insert);
    //             }

    //             return response()->json([
    //                 'status'  => true,
    //                 'message' => 'Data berhasil diimport'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Tidak ada data yang diimport'
    //             ]);
    //         }
    //     }

    //     return redirect('/');
    // }

    public function export_excel()
    {
        //Ambil value kategori yang akan diexport
        $kategori = Kategori::select(
            'kode_kategori',
            'nama_kategori',
            'deskripsi'
        )
            ->orderBy('id_kategori')
            ->get();

        //load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); //ambil sheet aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kategori');
        $sheet->setCellValue('C1', 'Nama Kategori');
        $sheet->setCellValue('D1', 'Deskripsi');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true); // Set header bold

        $no = 1; //Nomor value dimulai dari 1
        $baris = 2; //Baris value dimulai dari 2
        foreach ($kategori as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->kode_kategori);
            $sheet->setCellValue('C' . $baris, $value->nama_kategori);
            $sheet->setCellValue('D' . $baris, $value->deskripsi);
            $no++;
            $baris++;
        }

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); //set auto size untuk kolom
        }

        $sheet->setTitle('Data kategori'); //set judul sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); //set writer
        $filename = 'Data_kategori_' . date('Y-m-d_H-i-s') . '.xlsx'; //set nama file

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
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
