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
        $wilayah = $request->wilayah;
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;
        $search = $request->search;

        $query = \App\Models\Transaksi::with(['umkm.wilayah', 'detail.produk', 'user']);

        // Filter wilayah
        if ($wilayah) {
            $query->whereHas('umkm', function($q) use ($wilayah) {
                $q->where('id_wilayah', $wilayah);
            });
        }

        // Filter tanggal
        if ($tanggal_dari && $tanggal_sampai) {
            $query->whereBetween('tanggal_transaksi', [$tanggal_dari, $tanggal_sampai]);
        } elseif ($tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $tanggal_dari);
        } elseif ($tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $tanggal_sampai);
        }

        // Filter search (harus tetap dalam scope wilayah)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('umkm', function($q2) use ($search) {
                    $q2->where('nama_usaha', 'like', "%$search%")
                       ->orWhere('alamat', 'like', "%$search%");
                })
                ->orWhereHas('detail.produk', function($q3) use ($search) {
                    $q3->where('nama_produk', 'like', "%$search%");
                })
                ->orWhereHas('user', function($q4) use ($search) {
                    $q4->where('nama', 'like', "%$search%")
                       ->orWhere('alamat', 'like', "%$search%");
                })
                ->orWhereHas('umkm.wilayah', function($q5) use ($search) {
                    $q5->where('nama_wilayah', 'like', "%$search%");
                })
                ->orWhere('tanggal_transaksi', 'like', "%$search%");
            });
        }

        $transaksis = $query->latest()->get();
        $wilayahs = \App\Models\Wilayah::all();

        return view('admin.penjualan.index', compact('transaksis', 'wilayahs', 'wilayah', 'tanggal_dari', 'tanggal_sampai', 'search'));
    }

    public function exportPdf(Request $request)
    {
        $wilayah = $request->wilayah;
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;
        $search = $request->search;

        $query = \App\Models\Transaksi::with(['umkm.wilayah', 'detail.produk', 'user']);

        if ($wilayah) {
            $query->whereHas('umkm.wilayah', function($q) use ($wilayah) {
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
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('umkm', function($q2) use ($search) {
                    $q2->where('nama_usaha', 'like', "%$search%")
                       ->orWhere('alamat', 'like', "%$search%");
                })
                ->orWhereHas('detail.produk', function($q3) use ($search) {
                    $q3->where('nama_produk', 'like', "%$search%");
                })
                ->orWhereHas('user', function($q4) use ($search) {
                    $q4->where('nama', 'like', "%$search%")
                       ->orWhere('alamat', 'like', "%$search%");
                })
                ->orWhereHas('umkm.wilayah', function($q5) use ($search) {
                    $q5->where('nama_wilayah', 'like', "%$search%");
                })
                ->orWhere('tanggal_transaksi', 'like', "%$search%");
            });
        }

        $transaksis = $query->latest()->get();

        // Ambil keterangan dari data
        $wilayahNama = $transaksis->pluck('umkm.wilayah.nama_wilayah')->unique()->implode(', ');
        $umkmNama = $transaksis->pluck('umkm.nama_usaha')->unique()->implode(', ');

        $tanggalMin = $transaksis->min('tanggal_transaksi');
        $tanggalMax = $transaksis->max('tanggal_transaksi');
        $bulanMin = $tanggalMin ? \Carbon\Carbon::parse($tanggalMin)->translatedFormat('F Y') : null;
        $bulanMax = $tanggalMax ? \Carbon\Carbon::parse($tanggalMax)->translatedFormat('F Y') : null;

        $pdf = \PDF::loadView('admin.penjualan.pdf', compact(
            'transaksis', 'umkmNama', 'wilayahNama',
            'tanggalMin', 'tanggalMax', 'bulanMin', 'bulanMax'
        ));
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
        $search = $request->search;

        $query = \App\Models\Transaksi::with(['umkm.wilayah', 'detail.produk', 'user']);

        if ($wilayah) {
            $query->whereHas('umkm.wilayah', function($q) use ($wilayah) {
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
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('umkm', function($q2) use ($search) {
                    $q2->where('nama_usaha', 'like', "%$search%")
                       ->orWhere('alamat', 'like', "%$search%");
                })
                ->orWhereHas('detail.produk', function($q3) use ($search) {
                    $q3->where('nama_produk', 'like', "%$search%");
                })
                ->orWhereHas('user', function($q4) use ($search) {
                    $q4->where('nama', 'like', "%$search%")
                       ->orWhere('alamat', 'like', "%$search%");
                });
            });
        }

        $transaksis = $query->latest()->get();

        // Ambil keterangan dari data
        $wilayahNama = $transaksis->pluck('umkm.wilayah.nama_wilayah')->unique()->implode(', ');
        $umkmNama = $transaksis->pluck('umkm.nama_usaha')->unique()->implode(', ');

        $tanggalMin = $transaksis->min('tanggal_transaksi');
        $tanggalMax = $transaksis->max('tanggal_transaksi');
        $bulanMin = $tanggalMin ? \Carbon\Carbon::parse($tanggalMin)->translatedFormat('F Y') : null;
        $bulanMax = $tanggalMax ? \Carbon\Carbon::parse($tanggalMax)->translatedFormat('F Y') : null;

        // Siapkan data header
        $header = [
            ['Laporan Penjualan'],
            [$umkmNama ? "UMKM: $umkmNama" : ''],
            [$wilayahNama ? "Wilayah: $wilayahNama" : ''],
            [$tanggalMin && $tanggalMax ? "Periode: " . \Carbon\Carbon::parse($tanggalMin)->format('d-m-Y') . " s/d " . \Carbon\Carbon::parse($tanggalMax)->format('d-m-Y') : ''],
            [$bulanMin && $bulanMax ? "Bulan: $bulanMin - $bulanMax" : ''],
            ['UMKM', 'Wilayah', 'Tanggal', 'Produk', 'Harga', 'Jumlah', 'Total', 'Pembeli', 'Alamat Pembeli', 'Status']
        ];

        $rows = [];
        foreach ($transaksis as $trx) {
            foreach ($trx->detail as $detail) {
                $rows[] = [
                    $trx->umkm->nama_usaha ?? '-',
                    $trx->umkm->wilayah->nama_wilayah ?? '-',
                    $trx->tanggal_transaksi,
                    $detail->produk->nama_produk ?? '-',
                    $detail->harga_satuan,
                    $detail->jumlah,
                    $detail->jumlah * $detail->harga_satuan,
                    $trx->user->nama ?? '-',
                    $trx->user->alamat ?? '-',
                    $trx->status_pembayaran,
                ];
            }
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($header, null, 'A1');
        $sheet->fromArray($rows, null, 'A' . (count($header) + 1));

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'penjualan.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
