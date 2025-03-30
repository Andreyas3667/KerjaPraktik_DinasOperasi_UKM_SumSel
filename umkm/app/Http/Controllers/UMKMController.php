<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UMKM;

class UMKMController extends Controller
{
    public function index() {
        $umkms = UMKM::all();
        return view('umkm.home', compact('umkms'));
    }

    public function show($id) {
        $umkm = UMKM::findOrFail($id);
        return view('umkm.show', compact('umkm'));
    }

}
