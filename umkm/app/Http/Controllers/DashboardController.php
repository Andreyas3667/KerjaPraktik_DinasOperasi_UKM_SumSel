<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\News;
use App\Models\UMKM;
use App\Models\Wilayah;
use App\Models\Produk;
use App\Models\DetailTransaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Grafik penjualan per bulan (misal tabel transaksi ada kolom 'total' dan 'tanggal_transaksi')
        $penjualan = DB::table('transaksi')
            ->selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(total) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Grafik total UMKM per wilayah
        $umkmWilayah = UMKM::selectRaw('id_wilayah, COUNT(*) as total')
            ->groupBy('id_wilayah')
            ->pluck('total', 'id_wilayah');

        // Grafik produk paling laris (top 5)
        $produkLaris = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->select('produk.nama_produk', DB::raw('SUM(detail_transaksi.jumlah) as total'))
            ->groupBy('produk.nama_produk')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'produk.nama_produk');

        return view('admin.dashboard', compact('penjualan', 'umkmWilayah', 'produkLaris'));
    }
}
