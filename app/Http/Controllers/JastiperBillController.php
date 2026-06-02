<?php

namespace App\Http\Controllers;

use App\Models\JastiperBill;
use App\Models\Order;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class JastiperBillController extends Controller
{
    private function syncCompletedOrders(): void
    {
        Order::where('status', 'selesai')
            ->whereNotNull('jastiper_id')
            ->whereDoesntHave('jastiperBill')
            ->get()
            ->each(function ($order) {
                JastiperBill::create([
                    'order_id' => $order->id,
                    'jastiper_id' => $order->jastiper_id,
                    'amount' => 1000,
                    'status' => 'unpaid',
                    'note' => 'Biaya aplikasi per pesanan selesai.',
                ]);
            });
    }

    public function jastiperIndex()
    {
        $this->syncCompletedOrders();

        $bills = JastiperBill::with('order')
            ->where('jastiper_id', Auth::id())
            ->latest()
            ->paginate(10);

        $totalUnpaid = JastiperBill::where('jastiper_id', Auth::id())
            ->where('status', 'unpaid')
            ->sum('amount');

        return view('dashboard.jastiper-bills', compact('bills', 'totalUnpaid'));
    }

    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $this->syncCompletedOrders();

        $bills = JastiperBill::with(['order', 'jastiper'])
            ->latest()
            ->paginate(15);

        $totalUnpaid = JastiperBill::where('status', 'unpaid')->sum('amount');
        $totalPaid = JastiperBill::where('status', 'paid')->sum('amount');

        return view('dashboard.admin-jastiper-bills', compact('bills', 'totalUnpaid', 'totalPaid'));
    }

    public function markPaid(JastiperBill $bill)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $bill->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Tagihan berhasil ditandai sudah dibayar.');
    }
}