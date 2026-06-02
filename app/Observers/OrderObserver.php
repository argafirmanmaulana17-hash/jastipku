<?php

namespace App\Observers;

use App\Models\JastiperBill;
use App\Models\Order;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        if ($order->status !== 'selesai') {
            return;
        }

        if (! $order->jastiper_id) {
            return;
        }

        JastiperBill::firstOrCreate(
            [
                'order_id' => $order->id,
            ],
            [
                'jastiper_id' => $order->jastiper_id,
                'amount' => 1000,
                'status' => 'unpaid',
                'note' => 'Biaya aplikasi per pesanan selesai.',
            ]
        );
    }
}
