<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Akses;
use App\Models\User;
use App\Models\Level;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class AksesController extends Controller
{
    public function index()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            $breadcrumb = (object) [
                'title' => 'Hak Akses',
                'list' => ['Dashboard', 'Hak Akses']
            ];

            $activeMenu = 'akses'; //set menu yang sedang aktif

            $user = User::all();
            $level = Level::all();

            return view('akses.index', ['breadcrumb' => $breadcrumb, 'level' => $level, 'user' => $user, 'activeMenu' => $activeMenu]);
        } else {
            return back();
        }
    }

    public function list(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            if ($request->ajax()) {
                $data = Akses::with(['user', 'user.level'])
                    ->select('akses.id_akses', 'akses.id_user', 'akses.kelola_akun', 'akses.kelola_barang', 'akses.transaksi', 'akses.laporan')
                    ->join('users', 'users.id_user', '=', 'akses.id_user');

                // Add level filter if provided
                if ($request->has('id_level') && $request->id_level != '') {
                    $data->where('users.id_level', $request->id_level);
                }

                return DataTables::of($data)
                    ->addColumn('foto_profil', function ($row) {
                        return $row->user->foto_profil
                            ? '<img src="' . asset('storage/profile/' . $row->user->foto_profil) . '" width="50" style="border-radius:10px;">'
                            : '<img src="' . asset('default-avatar.jpg') . '" width="50" style="border-radius:10px;">';
                    })
                    ->addColumn('kelola_akun', function ($row) {
                        $checked = $row->kelola_akun ? 'checked' : '';
                        return '<div class="form-check d-flex justify-content-center">
                        <input type="checkbox" class="form-check-input access-checkbox" 
                            data-id="' . $row->id_akses . '" 
                            data-field="kelola_akun" ' . $checked . '>
                    </div>';
                    })
                    ->addColumn('kelola_barang', function ($row) {
                        $checked = $row->kelola_barang ? 'checked' : '';
                        return '<div class="form-check d-flex justify-content-center">
                        <input type="checkbox" class="form-check-input access-checkbox" 
                            data-id="' . $row->id_akses . '" 
                            data-field="kelola_barang" ' . $checked . '>
                    </div>';
                    })
                    ->addColumn('transaksi', function ($row) {
                        $checked = $row->transaksi ? 'checked' : '';
                        return '<div class="form-check d-flex justify-content-center">
                        <input type="checkbox" class="form-check-input access-checkbox" 
                            data-id="' . $row->id_akses . '" 
                            data-field="transaksi" ' . $checked . '>
                    </div>';
                    })
                    ->addColumn('laporan', function ($row) {
                        $checked = $row->laporan ? 'checked' : '';
                        return '<div class="form-check d-flex justify-content-center">
                        <input type="checkbox" class="form-check-input access-checkbox" 
                            data-id="' . $row->id_akses . '" 
                            data-field="laporan" ' . $checked . '>
                    </div>';
                    })
                    ->rawColumns(['foto_profil', 'kelola_akun', 'kelola_barang', 'transaksi', 'laporan'])
                    ->make(true);
            }
        }
    }

    public function update_akses(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            try {
                $akses = Akses::findOrFail($request->id);
                $field = $request->field;
                $akses->$field = $request->value;
                $akses->save();

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false]);
            }
        }
    }
}
