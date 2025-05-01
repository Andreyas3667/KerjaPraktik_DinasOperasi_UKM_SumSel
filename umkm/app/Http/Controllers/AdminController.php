<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
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
