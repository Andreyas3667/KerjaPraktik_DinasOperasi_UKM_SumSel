<?php

namespace App\Http\Controllers;

use App\Models\UMKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class UMKMController extends Controller
{
    public function index()
    {
        $umkms = UMKM::with('produk', 'wilayah', 'user')->get();
        return view('umkm.index', compact('umkms'));
    }

    public function show($id)
    {
        $umkm = UMKM::with('produk')->findOrFail($id);
        return view('umkm.show', compact('umkm'));
    }

    public function create()
    {
        return view('umkm.create');
    }

    /**
     * Tampilkan halaman kelola UMKM.
     */
    public function manage(Request $request)
    {
        $query = \App\Models\UMKM::query();

        if ($request->filled('wilayah')) {
            $query->where('id_wilayah', $request->wilayah);
        }

        // Jika ada fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_usaha', 'like', "%$search%")
                  ->orWhere('alamat', 'like', "%$search%")
                  ->orWhere('kontak', 'like', "%$search%");
            });
        }

        $umkms = $query->get();
        $wilayahs = \App\Models\Wilayah::all();

        return view('admin.umkm.manage', compact('umkms', 'wilayahs'));
    }

    /**
     * Simpan UMKM baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255', // nama penanggung jawab user
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'nama_usaha' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat' => 'required|string',
            'kontak' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'id_wilayah' => 'required|exists:wilayah,id_wilayah',
        ]);

        // 1. Insert ke tabel users
        $user = \App\Models\User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'umkm',
            'id_wilayah' => $validated['id_wilayah'],
        ]);
        // dd($user);
        // 2. Insert ke tabel umkm
        UMKM::create([
            'nama_usaha' => $validated['nama_usaha'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'alamat' => $validated['alamat'],
            'kontak' => $validated['kontak'],
            'longitude' => $validated['longitude'],
            'latitude' => $validated['latitude'],
            'id_wilayah' => $validated['id_wilayah'],
            'id_user' => $user->id_users, // foreign key ke users
        ]);

        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $umkm = UMKM::findOrFail($id);
        return view('umkm.edit', compact('umkm'));
    }

    /**
     * Perbarui data UMKM.
     */
    public function update(Request $request, $id)
    {
        $umkm = UMKM::findOrFail($id);
        $umkm->nama_usaha = $request->nama_usaha;
        $umkm->alamat = $request->alamat;
        $umkm->kontak = $request->kontak;
        $umkm->id_wilayah = $request->id_wilayah;
        $umkm->deskripsi = $request->deskripsi; // pastikan ini ada
        $umkm->longitude = $request->longitude;
        $umkm->latitude = $request->latitude;
        // ...field lain...
        $umkm->save();
        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil diperbarui.');
    }

    /**
     * Hapus UMKM.
     */
    public function destroy($id)
    {
        $umkm = \App\Models\UMKM::findOrFail($id);

        // Hapus semua produk yang terkait dengan UMKM ini
        $umkm->produk()->delete();

        // Set id_umkm pada transaksi menjadi null
        \App\Models\Transaksi::where('id_umkm', $umkm->id_umkm)->update(['id_umkm' => null]);

        $umkm->delete();
        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $umkms = UMKM::where('nama_usaha', 'like', "%$keyword%")
            ->orWhere('deskripsi', 'like', "%$keyword%")
            ->get();

        return view('umkm.index', compact('umkms'));
    }

    public function ajaxSearch(Request $request)
    {
        $query = UMKM::with('produk', 'wilayah', 'user');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_usaha', 'like', "%$search%")
                  ->orWhere('alamat', 'like', "%$search%")
                  ->orWhere('kontak', 'like', "%$search%");
            });
        }
        $umkms = $query->get();
        return view('admin.umkm.partials.table', compact('umkms'))->render();
    }
    public function detail($id)
    {
        $umkm = \App\Models\UMKM::with('produk')->findOrFail($id);
        return view('umkm.detail', compact('umkm'));
    }
    public function transaksi(Request $request, $id)
    {
        $umkm = \App\Models\UMKM::with('produk')->findOrFail($id);
        $data = $request->validate([
            'qty' => 'required|array',
            'qty.*' => 'integer|min:1'
        ]);
        \DB::beginTransaction();
        try {
            $trx = Transaksi::create([
                'id_user' => auth()->id() ?? 1, // atau guest user
                'id_umkm' => $umkm->id_umkm,
                'status_pembayaran' => 'pending',
                'total' => 0,
                'tanggal_transaksi' => now(),
            ]);
            $total = 0;
            foreach ($data['qty'] as $id_produk => $qty) {
                $produk = $umkm->produk->where('id_produk', $id_produk)->first();
                if ($produk && $qty > 0) {
                    if ($qty > $produk->stok) {
                        \DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Stok produk {$produk->nama_produk} tidak mencukupi!"
                        ], 422);
                    }
                    $subtotal = $produk->harga * $qty;
                    DetailTransaksi::create([
                        'id_transaksi' => $trx->id_transaksi,
                        'id_produk' => $produk->id_produk,
                        'jumlah' => $qty,
                        'harga_satuan' => $produk->harga,
                    ]);
                    $total += $subtotal;
                }
            }
            $trx->total = $total;
            $trx->save();
            \DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), // tampilkan pesan error detail
            ], 500);
        }
    }
}
