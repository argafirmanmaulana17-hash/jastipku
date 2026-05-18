<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller; // Menggunakan ini agar tidak error seperti kemarin
use App\Models\Order;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show(Order $order)
    {
        // Kunci Pintu: Hanya pembeli atau jastiper terkait yang boleh masuk!
        if (Auth::id() != $order->user_id && Auth::id() != $order->jastiper_id) {
            abort(403, 'Akses Ditolak: Ini bukan pesanan Anda.');
        }
        
        // Ambil riwayat pesan, urutkan dari yang paling lama ke yang terbaru
        $chats = $order->chats()->with('sender')->orderBy('created_at', 'asc')->get();

        // Tentukan siapa lawan bicaranya untuk ditampilkan di Header (Ubah jadi ==)
        $lawanBicara = Auth::id() == $order->user_id ? $order->jastiper->name : $order->user->name;

        return view('chat.index', compact('order', 'chats', 'lawanBicara'));
    }

    public function store(Request $request, Order $order)
    {
        // Validasi keamanan
        if (Auth::id() != $order->user_id && Auth::id() != $order->jastiper_id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Simpan pesan ke database
        Chat::create([
            'order_id'  => $order->id,
            'sender_id' => Auth::id(),
            'message'   => $request->message,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function getMessages(Request $request, Order $order)
    {
        // Kunci Pintu: Hanya pembeli atau jastiper terkait (Ubah jadi != dua-duanya)
        if (Auth::id() != $order->user_id && Auth::id() != $order->jastiper_id) {
            return response()->json([], 403);
        }

        // Ambil ID pesan terakhir yang ada di HP/Layar user saat ini
        $lastId = $request->query('last_id', 0);

        // Cari pesan yang ID-nya lebih baru dari $lastId
        $chats = $order->chats()
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($chat) {
                return [
                    'id'      => $chat->id,
                    'message' => $chat->message,
                    'is_me'   => $chat->sender_id == Auth::id(), // Pastikan pakai ==
                    'time'    => $chat->created_at->format('H:i')
                ];
            });

        return response()->json($chats);
    }
}