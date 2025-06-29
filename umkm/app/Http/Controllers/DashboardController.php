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

        $penjualanBulanan = DB::table('transaksi')
            ->selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(total) as total')
            ->whereYear('tanggal_transaksi', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $umkmWilayah = DB::table('umkm')
            ->selectRaw('id_wilayah, COUNT(*) as total')
            ->groupBy('id_wilayah')
            ->pluck('total', 'id_wilayah');

        $produkLaris = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->select('produk.nama_produk', DB::raw('SUM(detail_transaksi.jumlah) as total'))
            ->groupBy('produk.nama_produk')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'produk.nama_produk');

        $umkmPenjualan = DB::table('transaksi')
            ->join('umkm', 'transaksi.id_umkm', '=', 'umkm.id_umkm')
            ->select('umkm.nama_usaha', DB::raw('SUM(transaksi.total) as total'))
            ->whereYear('transaksi.tanggal_transaksi', $tahun)
            ->where('transaksi.status_pembayaran', 'selesai') // pastikan hanya transaksi sukses
            ->groupBy('umkm.nama_usaha')
            ->orderByDesc('total')
            ->pluck('total', 'umkm.nama_usaha');

        $tahunList = DB::table('transaksi')
            ->selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $penjualan = \App\Models\Transaksi::where('status_pembayaran', 'selesai')
            ->whereYear('tanggal_transaksi', $tahun)
            ->orderByDesc('tanggal_transaksi')
            ->get();

        // Kirim semua variabel ke view
        return view('admin.dashboard', [
            'penjualanBulanan' => $penjualanBulanan,
            'umkmWilayah' => $umkmWilayah,
            'produkLaris' => $produkLaris,
            'umkmPenjualan' => $umkmPenjualan,
            'tahun' => $tahun,
            'tahunList' => $tahunList,
            'penjualan' => $penjualan, // <-- tambahkan ini
        ]);
    }

    public function dashboard()
    {
        // Ambil daftar tahun dari transaksi
        $tahunList = DB::table('transaksi')
            ->selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Data lain yang ingin dikirim ke view, misal:
        $tahun = request('tahun', date('Y'));
        // ...tambahkan data lain sesuai kebutuhan...

        return view('admin.dashboard', compact('tahunList', 'tahun'));
    }

    public function userDashboard()
    {
        // Anda bisa menyesuaikan data yang ingin dikirim ke view dashboard user
        return view('dashboard'); // Pastikan file resources/views/dashboard.blade.php ada
    }

    // Jika belum ada, tambahkan juga method adminDashboard untuk admin:
    public function adminDashboard(Request $request)
    {
        // Ambil daftar tahun dari transaksi
        $tahunList = DB::table('transaksi')
            ->selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Pilih tahun aktif (default tahun sekarang)
        $tahun = $request->input('tahun', date('Y'));

        // Data lain yang ingin dikirim ke view, misal penjualan, grafik, dsb.
        // Contoh:
        $penjualan = \App\Models\Transaksi::where('status_pembayaran', 'selesai')
            ->whereYear('tanggal_transaksi', $tahun)
            ->orderByDesc('tanggal_transaksi')
            ->get();

        return view('admin.dashboard', compact('tahunList', 'tahun', 'penjualan'));
    }
}
