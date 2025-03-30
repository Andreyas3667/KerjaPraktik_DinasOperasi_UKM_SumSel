<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UMKM;

class UMKMController extends Controller
{
    public function index()
    {
        return response()->json(UMKM::all());
    }
}
