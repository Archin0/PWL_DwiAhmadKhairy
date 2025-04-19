<?php

namespace App\Http\Controllers;

use App\Models\Akses;
use App\Models\Barang;
use App\Models\Detail;
use App\Models\Supply;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{

    public function index()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->transaksi != 1) {
            return back();
        }

        $activeMenu = 'transaksi';
        $breadcrumb = (object) [
            'title' => 'Transaksi',
            'list' => ['Home', 'Transaksi']
        ];

        $barang = Barang::all(); // Make sure to import your Barang model

        return view('transaksi.index', ['activeMenu' => $activeMenu, 'breadcrumb' => $breadcrumb], compact('barang'));
    }

    public function indexList()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->transaksi != 1 && $cekAkses->kelola_barang != 1) {
            return back();
        }

        $activeMenu = 'transaksi_list';
        $breadcrumb = (object) [
            'title' => 'Data Transaksi',
            'list' => ['Home', 'Data Transaksi']
        ];

        return view('transaksi.list', ['activeMenu' => $activeMenu, 'breadcrumb' => $breadcrumb]);
    }

    public function list(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->transaksi != 1) {
            return back();
        }

        $transactions = Transaksi::with('user')
            ->select(['id_transaksi', 'kode_transaksi', 'pembeli', 'id_user', 'created_at'])
            ->orderBy('created_at', 'desc');

        return DataTables::of($transactions)
            ->addColumn('aksi', function ($row) {
                $printBtn = '<a href="' . url('transaksi/export_pdf/' . $row->id_transaksi) . '" target="_blank" class="btn btn-info btn-action" title="Cetak Struk"><i class="fas fa-print"></i> Cetak Struk</a>';
                $deleteBtn = '<button onclick="modalAction(\'' . url('/transaksi/' . $row->id_transaksi . '/delete_ajax') . '\')"  class="btn btn-delete btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';
                return $printBtn . $deleteBtn;
            })
            ->rawColumns(['aksi'])
            // ->toJson();
            ->make(true);
    }

    public function confirm_ajax(string $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            $transaksi = Transaksi::find($id);

            return view('transaksi.confirm_ajax', ['transaksi' => $transaksi]);
        }
    }

    public function delete_ajax(Request $request, $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                try {
                    DB::beginTransaction();

                    $transaksi = Transaksi::findOrFail($id);

                    // Kembalikan stok barang
                    foreach ($transaksi->detail as $detail) {
                        Barang::where('id_barang', $detail->id_barang)
                            ->increment('stok', $detail->jumlah);
                    }

                    // Hapus detail transaksi
                    $transaksi->detail()->delete();

                    // Hapus transaksi
                    $transaksi->delete();

                    DB::commit();

                    return response()->json(['status' => true]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                    ], 500);
                }
            }
            return redirect('/');
        }
    }

    public function store(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->transaksi != 1) {
            return back();
        }

        $request->validate([
            'kode_transaksi' => 'required',
            'pembeli' => 'required',
            'total_barang' => 'required|numeric',
            'diskon' => 'required|numeric',
            'total_harga' => 'required|numeric',
            'metode_pembayaran' => 'required|in:Card Only,Transfer',
            'items' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            // Create transaction
            $transaksi = Transaksi::create([
                'id_user' => Auth::id(),
                'kode_transaksi' => $request->kode_transaksi,
                'pembeli' => $request->pembeli,
                'total_barang' => $request->total_barang,
                'diskon' => $request->diskon,
                'total_harga' => $request->total_harga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create transaction details
            foreach ($request->items as $item) {
                Detail::create([
                    'id_barang' => $item['id_barang'],
                    'id_transaksi' => $transaksi->id_transaksi,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Update stock
                Barang::where('id_barang', $item['id_barang'])
                    ->decrement('stok', $item['jumlah']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'id_transaksi' => $transaksi->id_transaksi
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export_pdf($id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->transaksi != 1) {
            return back();
        }

        $transaksi = Transaksi::with(['user', 'detail.barang'])
            ->findOrFail($id);

        $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H-i-s');

        $pdf = PDF::loadView('transaksi.export_pdf', [
            'transaksi' => $transaksi,
            'timestamp' => $timestamp
        ]);

        $pdf->setPaper([0, 0, 300, 500], 'portrait'); // Lebih kecil dari sebelumnya
        $pdf->setOption("isRemoteEnabled", true);

        return $pdf->stream('Struk Transaksi ' . $transaksi->kode_transaksi . '.pdf', [
            'Attachment' => false
        ]);
    }

    public function export_transaksi()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->laporan == 1) {
            $transaksi = Transaksi::select('kode_transaksi', 'id_user', 'pembeli', 'total_harga', 'total_barang', 'created_at')
                ->orderBy('created_at')
                ->with('user')
                ->get();

            // Tambahkan GMT+7 ke timestamp
            $timestamp = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H-i-s');

            $pdf = PDF::loadView('laporan.exportpdf_transaksi', ['transaksi' => $transaksi, 'timestamp' => $timestamp]);
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption("isRemoteEnabled", true);

            return $pdf->stream('Data Transaksi ' . $timestamp . '.pdf', [
                'Attachment' => false
            ]);
        } else {
            return back();
        }
    }
}
