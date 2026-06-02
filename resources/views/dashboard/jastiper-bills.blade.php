@extends('layouts.app')

@section('title', 'Tagihan Aplikasi - JastipKu')

@section('content')
    <div class="pt-24 pb-20 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4">

            <div class="mb-6">
                <a href="{{ url('/dashboard/jastiper') }}" class="text-sm text-blue-600 font-semibold hover:underline">
                    ← Kembali ke Dashboard
                </a>

                <h1 class="text-3xl font-extrabold text-slate-900 mt-3">
                    Tagihan Aplikasi
                </h1>

                <p class="text-slate-500 mt-2">
                    Setiap pesanan yang selesai dikenakan biaya aplikasi Rp1.000.
                </p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-3xl p-6 mb-6 shadow-sm">
                <div class="text-sm text-slate-500">
                    Total Tagihan Belum Dibayar
                </div>

                <div class="text-4xl font-extrabold text-red-600 mt-2">
                    Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                </div>

                <div class="mt-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-2xl p-4 text-sm">
                    Untuk pembayaran tagihan, silakan hubungi admin JastipKu.
                    <br>
                    <a href="https://wa.me/6285943793833" class="font-bold underline" target="_blank">
                        Hubungi Admin
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-slate-100">
                    <h2 class="font-bold text-slate-900">
                        Riwayat Tagihan
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-5 py-3 text-left">Order</th>
                                <th class="px-5 py-3 text-left">Biaya</th>
                                <th class="px-5 py-3 text-left">Status</th>
                                <th class="px-5 py-3 text-left">Tanggal</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($bills as $bill)
                                <tr>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-blue-600">
                                            {{ $bill->order->kode_order ?? '#' . $bill->order_id }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            ID Order: {{ $bill->order_id }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 font-bold">
                                        Rp {{ number_format($bill->amount, 0, ',', '.') }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($bill->status === 'paid')
                                            <span
                                                class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold border border-green-100">
                                                Sudah Dibayar
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold border border-red-100">
                                                Belum Dibayar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-slate-500">
                                        {{ $bill->created_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-slate-400">
                                        Belum ada tagihan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-5 border-t border-slate-100">
                    {{ $bills->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
