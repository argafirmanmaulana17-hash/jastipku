<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Order;
use App\Models\OrderPriceOffer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show(Order $order)
    {
        if (Auth::id() != $order->user_id && Auth::id() != $order->jastiper_id) {
            abort(403, 'Akses Ditolak: Ini bukan pesanan Anda.');
        }

        $order->load(['user', 'jastiper', 'priceOffers.sender']);

        $chats = $order->chats()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        $lawanBicara = Auth::id() == $order->user_id
            ? ($order->jastiper->name ?? 'Jastiper')
            : ($order->user->name ?? 'Customer');

        return view('chat.index', compact('order', 'chats', 'lawanBicara'));
    }

    public function store(Request $request, Order $order)
    {
        if (Auth::id() != $order->user_id && Auth::id() != $order->jastiper_id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Chat::create([
            'order_id' => $order->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function getMessages(Request $request, Order $order)
    {
        if (Auth::id() != $order->user_id && Auth::id() != $order->jastiper_id) {
            return response()->json([], 403);
        }

        $lastId = $request->query('last_id', 0);

        $chats = $order->chats()
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($chat) {
                return [
                    'id' => $chat->id,
                    'message' => $chat->message,
                    'is_me' => $chat->sender_id == Auth::id(),
                    'time' => $chat->created_at->format('H:i'),
                ];
            });

        return response()->json($chats);
    }

    public function storePriceOffer(Request $request, Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id && $order->jastiper_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'harga_barang' => 'required|numeric|min:0',
            'ongkos_jastip' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        $total = $validated['harga_barang'] + $validated['ongkos_jastip'];

        OrderPriceOffer::where('order_id', $order->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        OrderPriceOffer::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'sender_role' => $user->role,
            'harga_barang' => $validated['harga_barang'],
            'ongkos_jastip' => $validated['ongkos_jastip'],
            'total_bayar' => $total,
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'pending',
        ]);

        $order->update([
            'harga_barang' => $validated['harga_barang'],
            'ongkos_jastip' => $validated['ongkos_jastip'],
            'total_bayar' => $total,
            'catatan_harga' => $validated['catatan'] ?? null,
            'status' => 'menunggu_persetujuan',
        ]);

        Chat::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'message' => "💰 Mengajukan penawaran harga:\nHarga barang: Rp ".number_format($validated['harga_barang'], 0, ',', '.').
                "\nOngkos jastip: Rp ".number_format($validated['ongkos_jastip'], 0, ',', '.').
                "\nTotal: Rp ".number_format($total, 0, ',', '.').
                ($validated['catatan'] ? "\nCatatan: {$validated['catatan']}" : ''),
        ]);

        return back()->with('success', 'Penawaran harga berhasil dikirim.');
    }

    public function acceptPriceOffer(Order $order, OrderPriceOffer $offer)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id && $order->jastiper_id !== $user->id) {
            abort(403);
        }

        if ($offer->order_id !== $order->id) {
            abort(404);
        }

        if ($offer->sender_id === $user->id) {
            return back()->withErrors([
                'offer' => 'Kamu tidak bisa menyetujui penawaran yang kamu buat sendiri.',
            ]);
        }

        if ($offer->status !== 'pending') {
            return back()->withErrors([
                'offer' => 'Penawaran ini sudah tidak aktif.',
            ]);
        }

        $offer->update([
            'status' => 'accepted',
        ]);

        OrderPriceOffer::where('order_id', $order->id)
            ->where('id', '!=', $offer->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        $order->update([
            'harga_barang' => $offer->harga_barang,
            'ongkos_jastip' => $offer->ongkos_jastip,
            'total_bayar' => $offer->total_bayar,
            'catatan_harga' => $offer->catatan,
            'harga_disetujui_at' => now(),
            'status' => 'proses',
        ]);

        Chat::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'message' => '✅ Harga disetujui. Pesanan masuk ke proses.',
        ]);

        return back()->with('success', 'Harga disetujui. Pesanan masuk ke proses.');
    }

    public function rejectPriceOffer(Order $order, OrderPriceOffer $offer)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id && $order->jastiper_id !== $user->id) {
            abort(403);
        }

        if ($offer->order_id !== $order->id) {
            abort(404);
        }

        if ($offer->sender_id === $user->id) {
            return back()->withErrors([
                'offer' => 'Kamu tidak bisa menolak penawaran yang kamu buat sendiri.',
            ]);
        }

        if ($offer->status !== 'pending') {
            return back()->withErrors([
                'offer' => 'Penawaran ini sudah tidak aktif.',
            ]);
        }

        $offer->update([
            'status' => 'rejected',
        ]);

        $order->update([
            'status' => 'menunggu_harga',
        ]);

        Chat::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'message' => '❌ Penawaran harga ditolak. Silakan ajukan harga baru.',
        ]);

        return back()->with('success', 'Penawaran harga ditolak. Silakan ajukan harga baru.');
    }

    public function getState(Order $order)
    {
        if (Auth::id() != $order->user_id && Auth::id() != $order->jastiper_id) {
            return response()->json([], 403);
        }

        $freshOrder = $order->fresh();

        $offers = $order->priceOffers()
            ->select('id', 'status', 'updated_at')
            ->orderBy('id')
            ->get();

        $offerSignature = $offers->map(function ($offer) {
            return $offer->id.'-'.$offer->status.'-'.optional($offer->updated_at)->timestamp;
        })->implode('|');

        return response()->json([
            'order_status' => $freshOrder->status,
            'order_updated_at' => optional($freshOrder->updated_at)->timestamp,
            'offer_signature' => $offerSignature,
        ]);
    }
}
