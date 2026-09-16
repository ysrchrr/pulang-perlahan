<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuperadminDashboardController extends Controller
{
    public function index(){

        $data = [
            'page_title' => 'Dashboard'
        ];
        return view('superadmin.dashboard', $data);
    }
}
