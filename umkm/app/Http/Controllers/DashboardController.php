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

        // Query produk terlaris
        $produkTerlaris = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->join('umkm', 'produk.id_umkm', '=', 'umkm.id_umkm')
            ->join('wilayah', 'umkm.id_wilayah', '=', 'wilayah.id_wilayah')
            ->select(
                'produk.nama_produk',
                'umkm.nama_usaha',
                'wilayah.nama_wilayah',
                DB::raw('SUM(detail_transaksi.jumlah) as jumlah_terjual')
            )
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'umkm.nama_usaha', 'wilayah.nama_wilayah')
            ->orderByDesc('jumlah_terjual')
            ->first();

        // Query Top 3 Produk Terlaris
        $topProdukTerlaris = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->join('umkm', 'produk.id_umkm', '=', 'umkm.id_umkm')
            ->join('wilayah', 'umkm.id_wilayah', '=', 'wilayah.id_wilayah')
            ->whereYear('transaksi.tanggal_transaksi', $tahun)
            ->where('transaksi.status_pembayaran', 'selesai')
            ->select(
                'produk.nama_produk',
                'produk.gambar',
                'umkm.nama_usaha',
                'wilayah.nama_wilayah',
                DB::raw('SUM(detail_transaksi.jumlah) as jumlah_terjual')
            )
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.gambar', 'umkm.nama_usaha', 'wilayah.nama_wilayah')
            ->orderByDesc('jumlah_terjual')
            ->limit(3)
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
            'produkTerlaris' => $produkTerlaris,
            'topProdukTerlaris' => $topProdukTerlaris,
        ]);
    }

    public function dashboard(Request $request)
    {
        // Ambil daftar tahun dari transaksi
        $tahunList = DB::table('transaksi')
            ->selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Data lain yang ingin dikirim ke view, misal:
        $tahun = $request->input('tahun', date('Y'));

        // Produk terlaris (ambil 1 teratas)
        $produkTerlaris = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->join('umkm', 'produk.id_umkm', '=', 'umkm.id_umkm')
            ->join('wilayah', 'umkm.id_wilayah', '=', 'wilayah.id_wilayah')
            ->select(
                'produk.nama_produk',
                'umkm.nama_usaha',
                'wilayah.nama_wilayah',
                DB::raw('SUM(detail_transaksi.jumlah) as jumlah_terjual')
            )
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'umkm.nama_usaha', 'wilayah.nama_wilayah')
            ->orderByDesc('jumlah_terjual')
            ->first();

        return view('admin.dashboard', [
            'tahunList' => $tahunList,
            'tahun' => $tahun,
            'produkTerlaris' => $produkTerlaris,
        ]);
    }

    public function userDashboard()
    {
        // Anda bisa menyesuaikan data yang ingin dikirim ke view dashboard user
        return view('dashboard'); // Pastikan file resources/views/dashboard.blade.php ada
    }

    // Jika belum ada, tambahkan juga method adminDashboard untuk admin:
    public function adminDashboard(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan');
        $minggu = $request->input('minggu');

        // Filter tanggal
        $query = \App\Models\Transaksi::where('status_pembayaran', 'selesai')
            ->whereYear('tanggal_transaksi', $tahun);

        if ($bulan) {
            $query->whereMonth('tanggal_transaksi', $bulan);
        }
        if ($minggu) {
            // Minggu ke-n dalam bulan
            $query->whereRaw('FLOOR((DAY(tanggal_transaksi)-1)/7)+1 = ?', [$minggu]);
        }

        $penjualan = $query->orderByDesc('tanggal_transaksi')->get();

        // UMKM dengan penjualan terbanyak (dari hasil penjualan yang sudah difilter)
        $umkmPenjualan = $penjualan->groupBy(fn($trx) => $trx->umkm->nama_usaha ?? '-')
            ->map(fn($items) => $items->sum('total'));

        // Produk terlaris (dari hasil penjualan yang sudah difilter)
        $produkTerlaris = collect();
        foreach ($penjualan as $trx) {
            foreach ($trx->detail as $d) {
                $key = $d->produk->nama_produk ?? '-';
                $produkTerlaris[$key] = ($produkTerlaris[$key] ?? 0) + $d->jumlah;
            }
        }
        // Ambil data produk terlaris beserta info produk (gambar, umkm, wilayah)
        $produkInfo = [];
        foreach ($penjualan as $trx) {
            foreach ($trx->detail as $d) {
                $produk = $d->produk;
                $produkInfo[$produk->nama_produk] = [
                    'nama_produk' => $produk->nama_produk,
                    'gambar' => $produk->gambar ?? null,
                    'nama_usaha' => $trx->umkm->nama_usaha ?? '-',
                    'nama_wilayah' => $trx->umkm->wilayah->nama_wilayah ?? '-',
                    'jumlah_terjual' => $produkTerlaris[$produk->nama_produk] ?? 0,
                ];
            }
        }
        // Top 3 produk terlaris
        $topProdukTerlaris = collect($produkInfo)
            ->sortByDesc('jumlah_terjual')
            ->take(3)
            ->map(function($item) {
                return (object)$item; // pastikan hasilnya object, bukan array
            });

        // Ambil daftar tahun dari transaksi
        $tahunList = \App\Models\Transaksi::selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin.dashboard', [
            'tahunList' => $tahunList,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu' => $minggu,
            'penjualan' => $penjualan,
            'umkmPenjualan' => $umkmPenjualan,
            'topProdukTerlaris' => $topProdukTerlaris,
        ]);
    }
}
