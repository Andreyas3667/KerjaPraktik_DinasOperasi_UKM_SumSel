<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin() {
        return view('admin.dashboard');
    }

    public function umkm() {
        return view('umkm.dashboard');
    }
}
