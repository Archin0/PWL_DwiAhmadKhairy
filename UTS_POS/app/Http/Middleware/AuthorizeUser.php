<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        $user_role = $request->user()->getRole(); //ambil data level_kode dari user yang login
        if (in_array($user_role, $roles)) { //cek apakah level_kode user yang login ada di array $roles
            return $next($request); //lanjutkan request
        }

        //jika tidak punya role, maka tampilkan pesan error
        abort(403, 'Forbidden. Anda tidak memiliki akses untuk melakukan aksi ini');
    }
}
