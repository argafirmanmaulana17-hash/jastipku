<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create()
    {
        // Ambil daftar alamat tersimpan milik user yang sedang login
    $savedAddresses = \App\Models\Address::where('user_id', auth()->id())->get();

    return view('order.create', compact('savedAddresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'whatsapp' => 'required|string|max:20',
            'lokasi_antar' => 'required|string|max:255',
            'kategori' => 'required|string',
            'lokasi_ambil' => 'required|string|max:255',
            'detail_pesanan' => 'required|string',
            'budget' => 'required|numeric|min:1000',
            'waktu' => 'nullable|string',
            'pembayaran' => 'required|in:dana,cash,transfer',
            'catatan' => 'nullable|string|max:500',
        ]);

        $validated['kode_order'] = Order::generateKode();
        $validated['status'] = 'pending';
        $validated['user_id'] = Auth::id();

        $order = Order::create($validated);
        // Jika user menulis label alamat baru di form order, otomatis simpan ke buku alamat
        if ($request->has('label_alamat_baru') && $request->label_alamat_baru != null) {
            \App\Models\Address::create([
                'user_id' => auth()->id(),
                'label' => $request->label_alamat_baru,
                'alamat_lengkap' => $request->lokasi_antar, 
            ]);
        }

        return redirect()->route('order.create')
            ->with('success', "Order berhasil! Kode order kamu: {$order->kode_order}. Simpan kode ini untuk tracking pesanan.");
    }
}
