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
        return view('umkm.dashboard');
    }

    public function produk()
    {
        if (auth()->check()) {
            $umkm = UMKM::where('id_user', auth()->id())->first();
            $produks = $umkm ? $umkm->produk : [];
        } else {
            // Tampilkan semua produk (atau filter sesuai kebutuhan)
            $produks = Produk::all();
        }
        return view('umkm.produk', compact('produks'));
    }

    public function laporan(Request $request)
    {
        $umkm = UMKM::where('id_user', auth()->id())->first();
        $transaksis = $umkm ? Transaksi::with('detail.produk')->where('id_umkm', $umkm->id_umkm)->get() : [];
        return view('umkm.laporan', compact('transaksis'));
    }

    public function exportPdf()
    {
        $umkm = UMKM::where('id_user', auth()->id())->first();
        $transaksis = $umkm ? Transaksi::with('detail.produk')->where('id_umkm', $umkm->id_umkm)->get() : [];
        $pdf = PDF::loadView('umkm.laporan_pdf', compact('transaksis'));
        return $pdf->download('laporan-penjualan-umkm.pdf');
    }

    public function profile()
    {
        $umkm = UMKM::where('id_user', auth()->id())->first();
        return view('umkm.profile', compact('umkm'));
    }

    public function updateProfile(Request $request)
    {
        $umkm = UMKM::where('id_user', auth()->id())->first();
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
