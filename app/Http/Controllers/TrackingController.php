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

        if ($request->filled('kode')) {
            $order = Order::with(['user', 'jastiper', 'items'])
                ->where('user_id', Auth::id())
                ->where('id', $request->kode)
                ->first();
        }

        $query = Order::with(['items'])
            ->where('user_id', Auth::id())
            ->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $orders = $query->paginate(5)->withQueryString();

        return view('tracking.index', compact('order', 'orders'));
    }
}
