<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Wilayah;
use PDF; // gunakan barryvdh/laravel-dompdf
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenjualanExport;

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
        $wilayahs = \App\Models\Wilayah::all();
        $wilayah = $request->wilayah;
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

        $query = \App\Models\Transaksi::with(['umkm.wilayah', 'detail.produk']);
        if ($wilayah) {
            $query->whereHas('umkm', function($q) use ($wilayah) {
                $q->where('id_wilayah', $wilayah);
            });
        }
        if ($tanggal_dari && $tanggal_sampai) {
            $query->whereBetween('tanggal_transaksi', [$tanggal_dari, $tanggal_sampai]);
        } elseif ($tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $tanggal_dari);
        } elseif ($tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $tanggal_sampai);
        }
        $transaksis = $query->latest()->get();

        return view('admin.penjualan.index', compact('transaksis', 'wilayahs', 'wilayah', 'tanggal_dari', 'tanggal_sampai'));
    }

    public function exportPdf(Request $request)
    {
        $wilayah = $request->wilayah;
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

        $query = \App\Models\Transaksi::with(['umkm.wilayah', 'detail.produk']);
        if ($wilayah) {
            $query->whereHas('umkm', function($q) use ($wilayah) {
                $q->where('id_wilayah', $wilayah);
            });
        }
        if ($tanggal_dari && $tanggal_sampai) {
            $query->whereBetween('tanggal_transaksi', [$tanggal_dari, $tanggal_sampai]);
        } elseif ($tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $tanggal_dari);
        } elseif ($tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $tanggal_sampai);
        }
        $transaksis = $query->latest()->get();

        $pdf = PDF::loadView('admin.penjualan.pdf', [
            'transaksis' => $transaksis,
            'wilayah' => $wilayah,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai
        ]);
        return $pdf->download('laporan-penjualan.pdf');
    }
    public function verifikasi(Request $request, $id)
    {
        $trx = \App\Models\Transaksi::with('detail.produk')->findOrFail($id);
        $trx->status_pembayaran = $request->status;
        $trx->save();

        // Jika konfirmasi selesai, kurangi stok produk
        if ($request->status == 'selesai') {
            foreach ($trx->detail as $detail) {
                $produk = $detail->produk;
                if ($produk) {
                    $produk->stok = max(0, $produk->stok - $detail->jumlah);
                    $produk->save();
                }
            }
        }

        return back()->with('success', 'Status pembayaran diperbarui.');
    }
    public function destroy($id)
    {
        $trx = Transaksi::findOrFail($id);
        $trx->delete();
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus.');
    }
    public function batal($id, Request $request)
    {
        $trx = \App\Models\Transaksi::findOrFail($id);
        $trx->status_pembayaran = 'batal';
        $trx->save();

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
    public function editJumlah(Request $request, $detailId)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);
        $detail = \App\Models\DetailTransaksi::findOrFail($detailId);
        $detail->jumlah = $request->jumlah;
        $detail->save();

        // Update total transaksi
        $trx = $detail->transaksi;
        $trx->total = $trx->detail->sum(function($d) {
            return $d->jumlah * $d->harga_satuan;
        });
        $trx->save();

        return redirect()->back()->with('success', 'Jumlah pesanan berhasil diubah.');
    }
    public function exportExcel(Request $request)
    {
        $wilayah = $request->wilayah;
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

        $query = \App\Models\Transaksi::with(['umkm.wilayah', 'detail.produk']);
        if ($wilayah) {
            $query->whereHas('umkm', function($q) use ($wilayah) {
                $q->where('id_wilayah', $wilayah);
            });
        }
        if ($tanggal_dari && $tanggal_sampai) {
            $query->whereBetween('tanggal_transaksi', [$tanggal_dari, $tanggal_sampai]);
        } elseif ($tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $tanggal_dari);
        } elseif ($tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $tanggal_sampai);
        }
        $transaksis = $query->latest()->get();

        return Excel::download(new PenjualanExport($transaksis), 'penjualan.xlsx');
    }
}
