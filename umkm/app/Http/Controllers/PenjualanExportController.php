<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PenjualanExportController extends Controller
{
    public function export(Request $request)
    {
        // Query data transaksi sesuai kebutuhan
        $transaksis = \App\Models\Transaksi::with(['umkm.wilayah', 'detail.produk', 'user'])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([[
            'UMKM', 'Wilayah', 'Tanggal', 'Produk', 'Harga', 'Jumlah', 'Total', 'Pembeli', 'Alamat Pembeli', 'Status'
        ]], null, 'A1');

        // Data
        $row = 2;
        foreach ($transaksis as $trx) {
            foreach ($trx->detail as $detail) {
                $sheet->fromArray([

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
                ], null, 'A' . $row);
                $row++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'penjualan.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
