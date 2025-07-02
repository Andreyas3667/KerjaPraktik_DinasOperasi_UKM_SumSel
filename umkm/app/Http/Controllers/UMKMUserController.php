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
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $umkm = $user && $user->umkm ? $user->umkm : \App\Models\UMKM::first();

        $tahun = $request->input('tahun', now()->year);

        $totalProduk = $umkm ? $umkm->produk()->whereYear('created_at', $tahun)->count() : 0;

        $totalPenjualan = \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm ?? 0)
            ->where('status_pembayaran', 'selesai')
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('total');

        $totalTransaksi = \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm ?? 0)
            ->where('status_pembayaran', 'selesai')
            ->whereYear('tanggal_transaksi', $tahun)
            ->count();

        // Ambil daftar tahun dari transaksi UMKM ini
        $tahunList = \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm)
            ->selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Penjualan bulanan filter tahun
        $penjualanBulanan = \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm)
            ->where('status_pembayaran', 'selesai')
            ->whereYear('tanggal_transaksi', $tahun)
            ->selectRaw('DATE_FORMAT(tanggal_transaksi, "%Y-%m") as bulan, SUM(total) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $produkTerlaris = \App\Models\DetailTransaksi::whereHas('transaksi', function($q) use ($umkm, $tahun) {
                $q->where('id_umkm', $umkm->id_umkm)
                  ->where('status_pembayaran', 'selesai')
                  ->whereYear('tanggal_transaksi', $tahun);
            })
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->select('produk.nama_produk', 'produk.gambar', \DB::raw('SUM(detail_transaksi.jumlah) as jumlah_terjual'))
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.gambar')
            ->orderByDesc('jumlah_terjual')
            ->limit(5)
            ->get();

        $produkTerjual = \App\Models\DetailTransaksi::whereHas('transaksi', function($q) use ($umkm, $tahun) {
                $q->where('id_umkm', $umkm->id_umkm)
                  ->where('status_pembayaran', 'selesai')
                  ->whereYear('tanggal_transaksi', $tahun);
            })
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->select('produk.nama_produk', \DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'))
            ->groupBy('produk.id_produk', 'produk.nama_produk')
            ->get();

        $penjualan = \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm)
            ->where('status_pembayaran', 'selesai')
            ->whereYear('tanggal_transaksi', $tahun)
            ->get();

        return view('umkm.dashboard', [
            'umkm' => $umkm,
            'totalProduk' => $totalProduk,
            'totalPenjualan' => $totalPenjualan,
            'totalTransaksi' => $totalTransaksi,
            'penjualanBulanan' => $penjualanBulanan,
            'produkTerlaris' => $produkTerlaris,
            'penjualan' => $penjualan,
            'tahunList' => $tahunList,
            'tahun' => $tahun,
            'produkTerjual' => $produkTerjual,
        ]);
    }

    public function produk()
    {
        if (auth()->check()) {
            $umkm = UMKM::where('id_user', auth()->id())->first();
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
        $tanggal_dari = $request->input('tanggal_dari');
        $tanggal_sampai = $request->input('tanggal_sampai');

        $query = \App\Models\Transaksi::with('detail.produk')
            ->where('id_umkm', $umkm->id_umkm)
            ->where('status_pembayaran', 'selesai');

        if ($tanggal_dari && $tanggal_sampai) {
            $query->whereBetween('tanggal_transaksi', [$tanggal_dari, $tanggal_sampai]);
        } elseif ($tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $tanggal_dari);
        } elseif ($tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $tanggal_sampai);
        }

        $transaksis = $query->get();

        // Grouping penjualan per bulan
        $dataPenjualanBulanan = $transaksis->filter(function($item) {
            return $item->tanggal_transaksi;
        })->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->tanggal_transaksi)->format('Y-m');
        })->map(function($items) {
            return $items->count();
        });

        return view('umkm.laporan', compact('transaksis', 'dataPenjualanBulanan'));
    }

    public function exportPdf()
    {
        if (auth()->check()) {
            $umkm = UMKM::where('id_user', auth()->id())->first();
        } else {
            $umkm = UMKM::first();
        }
        $transaksis = $umkm
            ? Transaksi::with('detail.produk')
                ->where('id_umkm', $umkm->id_umkm)
                ->where('status_pembayaran', 'selesai') // hanya yang sudah dikonfirmasi
                ->get()
            : [];
        $pdf = PDF::loadView('umkm.laporan_pdf', compact('transaksis'));
        return $pdf->download('laporan-penjualan.pdf');
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
    public function exportExcel(Request $request)
    {
        if (auth()->check()) {
            $umkm = \App\Models\UMKM::where('id_user', auth()->id())->first();
        } else {
            $umkm = \App\Models\UMKM::first();
        }
        $tanggal_dari = $request->input('tanggal_dari');
        $tanggal_sampai = $request->input('tanggal_sampai');

        $query = \App\Models\Transaksi::with('detail.produk')
            ->where('id_umkm', $umkm->id_umkm)
            ->where('status_pembayaran', 'selesai');

        if ($tanggal_dari && $tanggal_sampai) {
            $query->whereBetween('tanggal_transaksi', [$tanggal_dari, $tanggal_sampai]);
        } elseif ($tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $tanggal_dari);
        } elseif ($tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $tanggal_sampai);
        }

        $transaksis = $query->get();

        // Gunakan PhpSpreadsheet untuk export Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['Tanggal', 'Produk', 'Harga', 'Jumlah', 'Total']
        ], null, 'A1');

        $row = 2;
        foreach ($transaksis as $trx) {
            foreach ($trx->detail as $detail) {
                $sheet->fromArray([
                    $trx->tanggal_transaksi,
                    $detail->produk->nama_produk ?? '-',
                    $detail->harga_satuan,
                    $detail->jumlah,
                    $detail->jumlah * $detail->harga_satuan,
                ], null, 'A' . $row);
                $row++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'laporan-penjualan.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
