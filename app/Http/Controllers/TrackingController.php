<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $order = null;

        if ($request->filled('kode')) {
            $order = Order::with('jastiper')
                ->where('kode_order', strtoupper(trim($request->kode)))
                ->first();
        }

        return view('tracking.index', compact('order'));
    }
}
