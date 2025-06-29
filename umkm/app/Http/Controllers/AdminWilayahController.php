<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wilayah;

class AdminWilayahController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin_wilayah')->with('wilayah')->get();
        return view('admin.admin-wilayah.index', compact('admins'));
    }

    public function create()
    {
        $wilayahs = Wilayah::all();
        return view('admin.admin-wilayah.create', compact('wilayahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'id_wilayah' => 'required|exists:wilayah,id_wilayah',
        ]);
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin_wilayah',
            'id_wilayah' => $request->id_wilayah,
        ]);
        return redirect()->route('admin-wilayah.index')->with('success', 'Admin wilayah berhasil ditambahkan');
    }
    // Tambahkan edit, update, destroy sesuai kebutuhan
}
