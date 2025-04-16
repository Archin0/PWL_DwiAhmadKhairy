<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Akses;
use App\Models\User;

class AksesController extends Controller
{
    // Show View akses
    public function viewAkses()
    {
        $akses = Akses::join('users', 'users.id_user', '=', 'akses.id_user')
            ->select('akses.*', 'users.*')
            ->get();

        return view('kelola_akun.akses', compact('akses'));
    }

    // Change akses
    public function changeAkses($user, $akses)
    {
        $usr = Akses::where('id_user', $user)
            ->select($akses)
            ->first();

        if ($usr->$akses == 1) {
            Akses::where('id_user', $user)
                ->update([$akses => 0]);
        } else {
            Akses::where('id_user', $user)
                ->update([$akses => 1]);
        }

        $akses = Akses::join('users', 'users.id_user', '=', 'akses.id_user')
            ->select('akses.*', 'users.*')
            ->get();

        return view('manage_account.access_table', compact('akses'));
    }

    // Check akses
    public function checkAkses($user)
    {
        $check = Akses::where('id_user', $user)
            ->select('kelola_akun')
            ->first();

        if ($check->kelola_akun == 1)
            echo "benar";
        else
            echo "salah";
    }

    // Sidebar Refresh
    public function sidebarRefresh()
    {
        return view('templates.sidebar');
    }
}
