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
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Penjualan per bulan
        $penjualan = DB::table('transaksi')
            ->selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(total) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Total UMKM per wilayah
        $umkmWilayah = DB::table('umkm')
            ->selectRaw('id_wilayah, COUNT(*) as total')
            ->groupBy('id_wilayah')
            ->pluck('total', 'id_wilayah');

        // Produk paling laris
        $produkLaris = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->select('produk.nama_produk', DB::raw('SUM(detail_transaksi.jumlah) as total'))
            ->groupBy('produk.nama_produk')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'produk.nama_produk');

        // Penjualan per UMKM (grafik baru)
        $umkmPenjualan = DB::table('transaksi')
            ->join('umkm', 'transaksi.id_umkm', '=', 'umkm.id_umkm')
            ->select('umkm.nama_usaha', DB::raw('SUM(transaksi.total) as total'))
            ->whereYear('transaksi.tanggal_transaksi', $tahun)
            ->groupBy('umkm.nama_usaha')
            ->orderByDesc('total')
            ->pluck('total', 'umkm.nama_usaha');

        // Ambil daftar tahun dari transaksi
        $tahunList = DB::table('transaksi')
            ->selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin.dashboard', compact(
            'penjualan', 'umkmWilayah', 'produkLaris',
            'umkmPenjualan', 'tahun', 'tahunList'
        ));
    }

    public function dashboard()
    {
        // Anda bisa menyesuaikan data yang ingin dikirim ke view
        return view('dashboard');
    }
}
