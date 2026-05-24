<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create()
    {

        if (! auth()->user()->whatsapp_verified_at) {
            return redirect()->route('whatsapp.verify')
                ->with('error', 'Verifikasi WhatsApp dulu sebelum membuat pesanan.');
        }
        // Ambil daftar alamat tersimpan milik user yang sedang login.
        // Data ini dipakai untuk dropdown/pilihan alamat di form order.
        $savedAddresses = Address::where('user_id', auth()->id())->get();

        // Ambil daftar menu makanan/minuman yang masih aktif dari price list.
        // Data ini dipakai saat user memilih kategori makanan/minuman.
        $menuItems = MenuItem::where('aktif', true)
            ->orderBy('toko')
            ->orderBy('nama')
            ->get();

        // Kirim data alamat dan price list ke halaman form order.
        // Nanti di order/create.blade.php kita bisa pakai:
        // $savedAddresses untuk alamat
        // $menuItems untuk pilihan makanan/minuman
        return view('order.create', compact('savedAddresses', 'menuItems'));
    }

    public function store(Request $request)
    {

        if (! Auth::user()->whatsapp_verified_at) {
            return redirect()->route('whatsapp.verify')
                ->with('error', 'Verifikasi WhatsApp dulu sebelum membuat pesanan.');
        }
        // Validasi data dari form order.
        // WhatsApp tidak diambil dari form, tapi dari akun user yang login.
        // Detail pesanan opsional.
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'lokasi_antar' => 'required|string|max:255',
            'kategori' => 'required|string',
            'menu_item_id' => 'nullable|exists:menu_items,id',
            'lokasi_ambil' => 'required|string|max:255',
            'detail_pesanan' => 'nullable|string|max:1000',
            'budget' => 'nullable|numeric|min:0',
            'waktu' => 'nullable|string',
            'pembayaran' => 'required|in:dana,cash,transfer',
            'catatan' => 'nullable|string|max:500',
        ]);

        $validated['kode_order'] = Order::generateKode();
        $validated['status'] = 'pending';
        $validated['user_id'] = Auth::id();

        $user = Auth::user();
        $validated['whatsapp'] = $user->whatsapp ?? $user->no_hp ?? '';

        $validated['detail_pesanan'] = $validated['detail_pesanan'] ?? '';

        // Cek apakah kategori termasuk makanan/minuman.
        // Kalau iya, harga diambil dari price list.
        $kategori = strtolower($validated['kategori']);
        $isPriceList = str_contains($kategori, 'makanan') || str_contains($kategori, 'minuman');

        if ($isPriceList) {
            // Untuk makanan/minuman, user wajib memilih menu dari price list.
            if (empty($validated['menu_item_id'])) {
                return back()
                    ->withErrors(['menu_item_id' => 'Untuk makanan/minuman, pilih menu dari price list.'])
                    ->withInput();
            }

            // Ambil data menu yang dipilih user.
            $menu = MenuItem::findOrFail($validated['menu_item_id']);

            $ongkosJastip = 3000;

            $validated['jenis_harga'] = 'pricelist';
            $validated['harga_barang'] = $menu->harga;
            $validated['ongkos_jastip'] = $ongkosJastip;
            $validated['total_bayar'] = $menu->harga + $ongkosJastip;
            $validated['budget'] = $menu->harga;
            $validated['catatan_harga'] = 'Harga dari price list makanan/minuman.';

            $validated['nama_item_snapshot'] = $menu->nama;
            $validated['toko_snapshot'] = $menu->toko;
            $validated['kategori_item_snapshot'] = $menu->kategori;
        } else {
            // Untuk barang bebas seperti sapu, ember, alat tulis, dll.
            // Harga belum ditentukan di awal.
            // Nanti jastiper yang mengajukan harga barang + ongkos jastip.
            $validated['jenis_harga'] = 'penawaran';
            $validated['menu_item_id'] = null;
            $validated['harga_barang'] = null;
            $validated['ongkos_jastip'] = null;
            $validated['total_bayar'] = 0;
            $validated['budget'] = $validated['budget'] ?? 0;
            $validated['catatan_harga'] = null;

            $validated['nama_item_snapshot'] = $validated['detail_pesanan'];
            $validated['toko_snapshot'] = $validated['lokasi_ambil'];
            $validated['kategori_item_snapshot'] = $validated['kategori'];
        }

        // Simpan order ke database.
        $order = Order::create($validated);

        // Jika user menulis label alamat baru di form order,
        // otomatis simpan alamat tersebut ke buku alamat user.
        if ($request->has('label_alamat_baru') && $request->label_alamat_baru != null) {
            Address::create([
                'user_id' => auth()->id(),
                'label' => $request->label_alamat_baru,
                'alamat_lengkap' => $request->lokasi_antar,
            ]);
        }

        return redirect()->route('order.create')
            ->with('success', "Order berhasil! Kode order kamu: {$order->kode_order}. Simpan kode ini untuk tracking pesanan.");
    }
}
