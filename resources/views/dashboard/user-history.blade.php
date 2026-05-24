@extends('layouts.app')

@section('title', 'Riwayat Pesanan - JastipKu')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-sm text-blue-600 font-semibold hover:underline mb-3">
                    ← Kembali ke Beranda
                </a>

                <h1 class="font-display text-3xl font-800 text-slate-900">
                    Riwayat Pesanan
                </h1>

                <p class="text-slate-500 mt-2">
                    Lihat semua pesanan yang pernah kamu buat di JastipKu.
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm">
                <div class="text-xs text-slate-400 uppercase font-bold tracking-wider">
                    Total Pesanan
                </div>
                <div class="text-2xl font-extrabold text-slate-900">
                    {{ $orders->total() }}
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                ℹ️ {{ session('info') }}
            </div>
        @endif

        <!-- Summary Cards -->
       <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
        <div class="text-2xl mb-2">📦</div>
        <div class="text-sm text-slate-500">Semua Pesanan</div>
        <div class="text-2xl font-bold text-slate-900 mt-1">
            {{ $stats['total'] ?? $orders->total() }}
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5">
        <div class="text-2xl mb-2">🛵</div>
        <div class="text-sm text-slate-500">Sedang Aktif</div>
        <div class="text-2xl font-bold text-slate-900 mt-1">
            {{ $stats['active'] ?? 0 }}
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5">
        <div class="text-2xl mb-2">✅</div>
        <div class="text-sm text-slate-500">Selesai</div>
        <div class="text-2xl font-bold text-slate-900 mt-1">
            {{ $stats['selesai'] ?? 0 }}
        </div>
    </div>
</div>

        <!-- Orders -->
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="font-display text-xl font-700 text-slate-900">
                        Daftar Pesanan
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Data riwayat memakai snapshot saat order dibuat, jadi tidak berubah walau menu diedit.
                    </p>
                </div>

                <a href="{{ route('tracking') }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                    🎯 Buka Tracking
                </a>
            </div>

            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr class="text-left text-slate-500">
                                <th class="px-6 py-4 font-semibold">Kode</th>
                                <th class="px-6 py-4 font-semibold">Pesanan</th>
                                <th class="px-6 py-4 font-semibold">Kategori</th>
                                <th class="px-6 py-4 font-semibold">Total</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-mono font-bold text-blue-600">
                                            {{ $order->kode_order ?? '#' . $order->id }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            ID: {{ $order->id }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">
                                            {{ $order->nama_item_snapshot ?? $order->nama_barang ?? $order->judul ?? $order->detail_pesanan ?? 'Pesanan Jastip' }}
                                        </div>

                                        @if($order->toko_snapshot)
                                            <div class="text-xs text-slate-400 mt-1">
                                                Toko/Kantin: {{ $order->toko_snapshot }}
                                            </div>
                                        @endif

                                        <div class="text-xs text-slate-500 mt-1">
                                            Antar: {{ \Illuminate\Support\Str::limit($order->lokasi_antar, 45) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ ucfirst($order->kategori_item_snapshot ?? $order->kategori) }}
                                    </td>

                                    <td class="px-6 py-4 font-bold text-slate-900">
                                        Rp {{ number_format($order->total_bayar ?: $order->budget, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($order->status == 'pending')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                                Menunggu
                                            </span>
                                        @elseif($order->status == 'menunggu_harga')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-100">
                                                Menunggu Harga
                                            </span>
                                        @elseif($order->status == 'menunggu_persetujuan')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                                Menunggu Persetujuan
                                            </span>
                                        @elseif($order->status == 'proses')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                                Diproses
                                            </span>
                                        @elseif($order->status == 'otw')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                                Diantar
                                            </span>
                                        @elseif($order->status == 'selesai')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                                Batal
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}
                                        <div class="text-xs text-slate-400">
                                            {{ $order->created_at ? $order->created_at->format('H:i') : '' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('tracking', ['kode' => $order->id]) }}"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100 hover:bg-blue-100 transition">
                                            🔎 Lacak
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-5xl mb-4">📦</div>
                    <div class="font-semibold text-slate-700">
                        Belum ada riwayat pesanan
                    </div>
                    <div class="text-sm text-slate-400 mt-1">
                        Pesanan yang kamu buat akan muncul di halaman ini.
                    </div>

                    <a href="{{ route('order.create') }}"
                        class="inline-flex items-center justify-center mt-5 px-5 py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                        Buat Pesanan Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection