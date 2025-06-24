<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $penjualan = \App\Models\Transaksi::all();
        return view('admin.dashboard', compact('penjualan'));
    }

    public function manageUsers()
    {
        return view('admin.users');
    }

    public function manageNews()
    {
        return view('admin.news');
    }
}
