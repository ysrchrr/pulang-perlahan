<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardPetakomController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Dashboard Petakom',
        ];

        return view('dashboard_petakom.dashboard_admin', $data);
    }
}
