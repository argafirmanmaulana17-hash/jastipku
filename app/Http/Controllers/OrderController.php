<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
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

        $savedAddresses = Address::where('user_id', auth()->id())->get();

        $menuItems = MenuItem::where('aktif', true)
            ->orderBy('toko')
            ->orderBy('nama')
            ->get();

        return view('order.create', compact('savedAddresses', 'menuItems'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->whatsapp_verified_at) {
            return redirect()->route('whatsapp.verify')
                ->with('error', 'Verifikasi WhatsApp dulu sebelum membuat pesanan.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'lokasi_antar' => 'required|string|max:255',
            'kategori' => 'required|string',

            'menu_item_id' => 'nullable|exists:menu_items,id',
            'menu_items' => 'nullable|array',
            'menu_items.*.id' => 'nullable|exists:menu_items,id',
            'menu_items.*.qty' => 'nullable|integer|min:0|max:99',

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

        $kategori = strtolower($validated['kategori']);
        $isPriceList = str_contains($kategori, 'makanan') || str_contains($kategori, 'minuman');

        if ($isPriceList) {
            $selectedItems = collect($request->input('menu_items', []))
                ->filter(function ($item) {
                    return ! empty($item['id']) && isset($item['qty']) && (int) $item['qty'] > 0;
                });

            if ($selectedItems->isEmpty()) {
                return back()
                    ->withErrors(['menu_items' => 'Pilih minimal satu menu makanan/minuman.'])
                    ->withInput();
            }

            $menuIds = $selectedItems->pluck('id')->toArray();

            $menus = MenuItem::whereIn('id', $menuIds)
                ->where('aktif', true)
                ->get()
                ->keyBy('id');

            $totalBarang = 0;
            $snapshotNames = [];
            $snapshotToko = null;
            $snapshotKategori = 'makanan';

            foreach ($selectedItems as $item) {
                $menu = $menus->get((int) $item['id']);

                if (! $menu) {
                    return back()
                        ->withErrors(['menu_items' => 'Ada menu yang tidak valid atau sudah nonaktif.'])
                        ->withInput();
                }

                $qty = max(1, (int) $item['qty']);
                $subtotal = $menu->harga * $qty;

                $totalBarang += $subtotal;
                $snapshotNames[] = $menu->nama.' x'.$qty;

                if (! $snapshotToko && $menu->toko) {
                    $snapshotToko = $menu->toko;
                }
            }

            $totalQty = $selectedItems->sum(function ($item) {
                return (int) $item['qty'];
            });

            if ($totalQty <= 3) {
                $ongkosJastip = 3000;
            } elseif ($totalQty <= 8) {
                $ongkosJastip = 5000;
            } else {
                $ongkosJastip = 10000;
            }

            $validated['jenis_harga'] = 'pricelist';
            $validated['menu_item_id'] = null;
            $validated['harga_barang'] = $totalBarang;
            $validated['ongkos_jastip'] = $ongkosJastip;
            $validated['total_bayar'] = $totalBarang + $ongkosJastip;
            $validated['budget'] = $totalBarang;
            $validated['catatan_harga'] = 'Harga dari price list makanan/minuman.';

            $validated['nama_item_snapshot'] = implode(', ', $snapshotNames);
            $validated['toko_snapshot'] = $snapshotToko;
            $validated['kategori_item_snapshot'] = $snapshotKategori;
            $validated['lokasi_ambil'] = $snapshotToko ?: $validated['lokasi_ambil'];

            $order = Order::create($validated);

            foreach ($selectedItems as $item) {
                $menu = $menus->get((int) $item['id']);
                $qty = max(1, (int) $item['qty']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menu->id,
                    'nama_item' => $menu->nama,
                    'toko' => $menu->toko,
                    'kategori' => $menu->kategori,
                    'qty' => $qty,
                    'harga_satuan' => $menu->harga,
                    'subtotal' => $menu->harga * $qty,
                ]);
            }
        } else {
            $validated['jenis_harga'] = 'penawaran';
            $validated['menu_item_id'] = null;
            $validated['harga_barang'] = null;
            $validated['ongkos_jastip'] = null;
            $validated['total_bayar'] = 0;
            $validated['budget'] = $validated['budget'] ?? 0;
            $validated['catatan_harga'] = null;

            $validated['nama_item_snapshot'] = $validated['detail_pesanan'] ?: 'Pesanan request';
            $validated['toko_snapshot'] = $validated['lokasi_ambil'];
            $validated['kategori_item_snapshot'] = $validated['kategori'];

            $order = Order::create($validated);
        }

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
