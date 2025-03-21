<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\UMKM;

class UMKMController extends Controller
{
    public function showMap()
    {
        $umkms = UMKM::all();
        return view('maps', compact('umkms'));
    }
}
