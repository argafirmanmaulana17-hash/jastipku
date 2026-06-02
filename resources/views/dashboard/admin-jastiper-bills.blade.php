@extends('layouts.app')

@section('title', 'Tagihan Jastiper - Admin JastipKu')

@section('content')
    <div class="pt-24 pb-20 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4">

            <div class="mb-6">
                <a href="{{ route('dashboard.admin') }}" class="text-sm text-blue-600 font-semibold hover:underline">
                    ← Kembali ke Admin
                </a>

                <h1 class="text-3xl font-extrabold text-slate-900 mt-3">
                    Tagihan Jastiper
                </h1>

                <p class="text-slate-500 mt-2">
                    Kelola biaya aplikasi Rp1.000 per pesanan selesai.
                </p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="text-sm text-slate-500">Total Belum Dibayar</div>
                    <div class="text-3xl font-extrabold text-red-600 mt-2">
                        Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="text-sm text-slate-500">Total Sudah Dibayar</div>
                    <div class="text-3xl font-extrabold text-green-600 mt-2">
                        Rp {{ number_format($totalPaid, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-5 py-3 text-left">Jastiper</th>
                                <th class="px-5 py-3 text-left">Order</th>
                                <th class="px-5 py-3 text-left">Biaya</th>
                                <th class="px-5 py-3 text-left">Status</th>
                                <th class="px-5 py-3 text-left">Tanggal</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($bills as $bill)
                                <tr>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-900">
                                            {{ $bill->jastiper->name ?? 'Jastiper' }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{ $bill->jastiper->whatsapp ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-bold text-blue-600">
                                            {{ $bill->order->kode_order ?? '#' . $bill->order_id }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 font-bold">
                                        Rp {{ number_format($bill->amount, 0, ',', '.') }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($bill->status === 'paid')
                                            <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold border border-green-100">
                                                Sudah Dibayar
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold border border-red-100">
                                                Belum Dibayar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-slate-500">
                                        {{ $bill->created_at->format('d M Y H:i') }}
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        @if ($bill->status === 'unpaid')
                                            <form method="POST" action="{{ route('dashboard.admin.jastiper-bills.paid', $bill->id) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="px-3 py-2 rounded-lg bg-green-600 text-white text-xs font-bold hover:bg-green-700">
                                                    Tandai Dibayar
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-slate-400">
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