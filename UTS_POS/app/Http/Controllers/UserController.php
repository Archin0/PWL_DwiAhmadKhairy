<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Akses;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;


class UserController extends Controller
{
    public function index()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            $breadcrumb = (object) [
                'title' => 'Daftar User',
                'list' => ['Dashboard', 'Daftar User']
            ];

            $activeMenu = 'user'; //set menu yang sedang aktif

            $level = Level::all();

            return view('user.index', ['breadcrumb' => $breadcrumb, 'level' => $level, 'activeMenu' => $activeMenu]);
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
                $data = User::select('id_user', 'nama', 'username', 'foto_profil', 'id_level')
                    ->with('level');

                // Filter data user berdasarkan id_level
                if ($request->id_level) {
                    $data->where('id_level', $request->id_level);
                }

                return DataTables::of($data)
                    ->addColumn('foto_profil', function ($row) {
                        return $row->foto_profil
                            ? '<img src="' . asset('storage/' . $row->foto_profil) . '" width="50" style="border-radius:10px;">'
                            : '<img src="' . asset('default-avatar.jpg') . '" width="50" style="border-radius:10px;">';
                    })
                    ->addColumn('aksi', function ($row) {

                        $btn = '<button onclick="modalAction(\'' . url('/user/' . $row->id_user .
                            '/edit_ajax') . '\')" class="btn btn-edit btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                        $btn .= '<button onclick="modalAction(\'' . url('/user/' . $row->id_user .
                            '/delete_ajax') . '\')"  class="btn btn-delete btn-danger btn-sm"><i class="fas fa-trash"></i></button> ';

                        return $btn;
                    })
                    ->rawColumns(['foto_profil', 'aksi'])
                    ->make(true);
            }
        }
    }

    public function create_ajax()
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            $level = Level::select('id_level', 'nama_level')->get();

            return view('user.create_ajax')
                ->with('level', $level);
        }
    }

    public function store_ajax(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            // cek apakah request berupa ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'id_level' => 'required|integer',
                    'username' => 'required|string|min:3|unique:users,username',
                    'nama' => 'required|string|max:100',
                    'password' => 'required|min:5'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false, // response status, false: error/gagal, true: berhasil
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors() // pesan error validasi
                    ]);
                }

                // Hash password before saving
                $data = $request->all();
                $data['password'] = Hash::make($request->password);

                // Create the user and get the instance
                $user = User::create($data);

                // Create access based on user level
                $aksesData = [
                    'id_user' => $user->id_user,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                if ($request->id_level == 1) {
                    $aksesData['kelola_akun'] = true;
                    $aksesData['kelola_barang'] = true;
                    $aksesData['transaksi'] = true;
                    $aksesData['laporan'] = true;
                } else if ($request->id_level == 2) {
                    $aksesData['kelola_akun'] = false;
                    $aksesData['kelola_barang'] = false;
                    $aksesData['transaksi'] = true;
                    $aksesData['laporan'] = false;
                }

                // Create the access record
                Akses::create($aksesData);

                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil disimpan'
                ]);
            }

            return redirect('/');
        }
    }

    public function edit_ajax(string $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            $user = User::find($id);
            $level = Level::select('id_level', 'nama_level')->get();

            return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
        }
    }

    public function update_ajax(Request $request, $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'id_level' => 'required|integer',
                    'username' => 'required|max:20|unique:users,username,' . $id . ',id_user',
                    'nama' => 'required|max:100',
                    'password' => 'nullable|min:5|max:20'
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
                $check = User::find($id);
                if ($check) {
                    if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request
                        $request->request->remove('password');
                    }
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

        if ($cekAkses->kelola_akun == 1) {
            $user = User::find($id);

            return view('user.confirm_ajax', ['user' => $user]);
        }
    }

    public function delete_ajax(Request $request, $id)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $user = User::find($id);
                $akses = Akses::where('id_user', $user->id_user)->first();

                if ($user) {
                    // Hapus foto profil jika ada
                    if ($user->foto_profil) {
                        Storage::disk('public')->delete($user->foto_profil);
                    }
                    $akses->delete();
                    $user->delete();
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

        if ($cekAkses->kelola_akun == 1) {
            return view('user.import');
        }
    }

    public function import_ajax(Request $request)
    {
        $id_user = Auth::id();
        $cekAkses = Akses::where('id_user', $id_user)->first();

        if ($cekAkses->kelola_akun == 1) {
            if ($request->ajax() || $request->wantsJson()) {

                $rules = [
                    // Validasi file harus xlsx, maksimal 1MB
                    'file_user' => ['required', 'mimes:xlsx', 'max:1024']
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
                $file = $request->file('file_user');

                // Membuat reader untuk file excel dengan format Xlsx
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true); // Hanya membaca data saja

                // Load file excel
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

                // Ambil data excel sebagai array
                $data = $sheet->toArray(null, false, true, true);
                $insert = [];
                $errors = [];

                // Pastikan data memiliki lebih dari 1 baris (header + data)
                if (count($data) > 1) {
                    // Pertama, validasi setiap baris id_level
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // Baris pertama adalah header, jadi lewati
                            $levelId = $value['A'];
                            // Cek apakah id_level ada di tabel m_level
                            if (!Level::where('id_level', $levelId)->exists()) {
                                $errors["baris_$baris"] = "Level dengan ID {$levelId} tidak terdaftar.";
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

                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // Baris pertama adalah header, jadi lewati
                            $insert[] = [
                                'id_level' => $value['A'],
                                'username' => $value['B'],
                                'nama' => $value['C'],
                                'password' => hash::make($value['D']),
                                'created_at'  => now(),
                            ];
                        }
                    }

                    if (count($insert) > 0) {
                        // Insert data ke database, jika data sudah ada, maka diabaikan
                        User::insertOrIgnore($insert);
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
}
