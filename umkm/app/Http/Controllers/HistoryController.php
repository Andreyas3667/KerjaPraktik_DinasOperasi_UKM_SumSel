<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class HistoryController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['umkm', 'detail.produk'])
            ->where('id_user', auth()->id())
            ->orderByDesc('tanggal_transaksi')
            ->get();

        return view('history.index', compact('transaksis'));
    }
}
