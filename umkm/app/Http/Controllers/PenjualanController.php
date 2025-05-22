<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Wilayah;
use PDF; // gunakan barryvdh/laravel-dompdf

class PenjualanController extends Controller
{
    public function history(Request $request)
    {
        $transaksi = Transaksi::with('umkm', 'user')->latest()->paginate(20);
        return view('admin.penjualan.history', compact('transaksi'));
    }

    public function laporan(Request $request)
    {
        $wilayah = $request->wilayah;
        $query = Transaksi::with('umkm', 'user');
        if ($wilayah) {
            $query->whereHas('umkm', function($q) use ($wilayah) {
                $q->where('id_wilayah', $wilayah);
            });
        }
        $laporan = $query->get();
        return view('admin.penjualan.laporan', compact('laporan', 'wilayah'));
    }

    public function index(Request $request)
    {
        $wilayahs = Wilayah::all();
        $wilayah = $request->wilayah;

        $query = Transaksi::with(['umkm.wilayah', 'detail.produk']);
        if ($wilayah) {
            $query->whereHas('umkm', function($q) use ($wilayah) {
                $q->where('id_wilayah', $wilayah);
            });
        }
        $transaksis = $query->latest()->get();

        return view('admin.penjualan.index', compact('transaksis', 'wilayahs', 'wilayah'));
    }

    public function exportPdf(Request $request)
    {
        $wilayah = $request->wilayah;
        $query = Transaksi::with(['umkm.wilayah', 'detail.produk']);
        if ($wilayah) {
            $query->whereHas('umkm', function($q) use ($wilayah) {
                $q->where('id_wilayah', $wilayah);
            });
        }
        $transaksis = $query->latest()->get();

        $pdf = PDF::loadView('admin.penjualan.pdf', compact('transaksis', 'wilayah'));
        return $pdf->download('laporan-penjualan.pdf');
    }
}
