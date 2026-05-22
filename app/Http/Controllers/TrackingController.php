<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $order = null;

        // 1. Kotak Gede pelacakan aktif akan muncul jika ada parameter 'kode' (Picu dari klik baris tabel)
        if ($request->filled('kode')) {
            $order = Order::with(['jastiper'])
                ->where('id', $request->kode)
                ->first();
        }

        // 2. Logika Utama: Filter daftar riwayat pesanan di bawahnya hanya dengan TANGGAL
        $query = Order::where('user_id', Auth::id())->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Pakai withQueryString() agar filter tanggal tidak hilang saat ganti halaman pagination
        $orders = $query->paginate(5)->withQueryString();

        return view('tracking.index', compact('order', 'orders'));
    }
}
