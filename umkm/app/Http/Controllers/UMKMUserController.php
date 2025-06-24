<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UMKM;
use App\Models\Produk;
use App\Models\Transaksi;
use PDF;
use Auth;

class UMKMUserController extends Controller
{
    public function dashboard()
    {
        // Jika login, pakai UMKM user. Jika tidak, pakai UMKM pertama
        $user = auth()->user();
        if ($user && $user->umkm) {
            $umkm = $user->umkm;
        } else {
            $umkm = \App\Models\UMKM::first();
            if (!$umkm) {
                // Jika belum ada data UMKM sama sekali
                return view('umkm.dashboard', [
                    'totalProduk' => 0,
                    'totalPenjualan' => 0,
                    'totalTransaksi' => 0,
                    'penjualanBulanan' => collect([]),
                    'produkTerlaris' => collect([]),
                ]);
            }
        }

        $totalProduk = $umkm->produks()->count();
        $totalPenjualan = \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm)->sum('total');
        $totalTransaksi = \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm)->count();

        $penjualanBulanan = \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm)
            ->selectRaw('DATE_FORMAT(tanggal_transaksi, "%Y-%m") as bulan, SUM(total) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $produkTerlaris = \App\Models\DetailTransaksi::whereHas('transaksi', function($q) use ($umkm) {
                $q->where('id_umkm', $umkm->id_umkm);
            })
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->select('produk.nama_produk', 'produk.gambar', \DB::raw('SUM(detail_transaksi.jumlah) as jumlah_terjual'))
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.gambar')
            ->orderByDesc('jumlah_terjual')
            ->limit(5)
            ->get();

        $penjualan = \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm)->get();

        return view('umkm.dashboard', [
            'totalProduk' => $totalProduk,
            'totalPenjualan' => $totalPenjualan,
            'totalTransaksi' => $totalTransaksi,
            'penjualanBulanan' => $penjualanBulanan,
            'produkTerlaris' => $produkTerlaris,
            'penjualan' => $penjualan,
        ]);
    }

    public function produk()
    {
        if (auth()->check()) {
            $umkm = UMKM::where('id_user', auth()->id())->first();
            $produks = $umkm ? $umkm->produk : [];
        } else {
            // Tampilkan produk dari UMKM pertama (dummy)
            $umkm = UMKM::first();
            $produks = $umkm ? $umkm->produk : [];
        }
        return view('umkm.produk', compact('produks'));
    }

    public function laporan(Request $request)
    {
        if (auth()->check()) {
            $umkm = UMKM::where('id_user', auth()->id())->first();
        } else {
            $umkm = UMKM::first();
        }
        $transaksis = $umkm ? Transaksi::with('detail.produk')->where('id_umkm', $umkm->id_umkm)->get() : [];
        return view('umkm.laporan', compact('transaksis'));
    }

    public function exportPdf()
    {
        if (auth()->check()) {
            $umkm = UMKM::where('id_user', auth()->id())->first();
        } else {
            $umkm = UMKM::first();
        }
        $transaksis = $umkm ? Transaksi::with('detail.produk')->where('id_umkm', $umkm->id_umkm)->get() : [];
        $pdf = PDF::loadView('umkm.laporan_pdf', compact('transaksis'));
        return $pdf->download('laporan-penjualan-umkm.pdf');
    }

    public function profile()
    {
        if (auth()->check()) {
            $umkm = UMKM::where('id_user', auth()->id())->first();
        } else {
            $umkm = UMKM::first();
        }
        return view('umkm.profile', compact('umkm'));
    }

    public function updateProfile(Request $request)
    {
        if (auth()->check()) {
            $umkm = UMKM::where('id_user', auth()->id())->first();
        } else {
            $umkm = UMKM::first();
        }
        $request->validate([
            'nama_usaha' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|max:2048',
        ]);
        if ($umkm) {
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('umkm', 'public');
                $umkm->foto = $fotoPath;
            }
            $umkm->nama_usaha = $request->nama_usaha;
            $umkm->alamat = $request->alamat;
            $umkm->kontak = $request->kontak;
            $umkm->deskripsi = $request->deskripsi;
            $umkm->save();
        }
        return redirect()->route('umkm.profile')->with('success', 'Profil berhasil diperbarui');
    }
}
