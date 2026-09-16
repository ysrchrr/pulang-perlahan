<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;

class RekapPegawaiController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Rekap Pegawai',
            'today' => dayIndo(date('Y-m-d')) . ', ' . tglIndo(date('Y-m-d')),
            'total_pegawai' => UserRole::where('roles_id', 6)->whereNotIn('users_id', [1, 2])->count()
        ];

        return view('dashboard.rekap_pegawai', $data);
    }
}
