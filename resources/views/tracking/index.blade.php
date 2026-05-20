@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 animate-fade-in">
    
    <div class="mb-10 text-center flex flex-col items-center justify-center">
        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-2xl mb-3 shadow-sm border border-blue-100">
            🎯
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Lacak & Riwayat Pesanan</h1>
        <p class="text-sm text-slate-500 mt-2 max-w-md">Cari histori belanjamu berdasarkan kalender tanggal dan klik baris tabel untuk memuat detail pelacakan.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-8">
        <form action="{{ route('tracking') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full flex-1">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pilih Tanggal Transaksi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm">📅</span>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm text-slate-700 font-medium">
                </div>
            </div>
            <div class="w-full sm:w-auto flex gap-2">
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-6 py-3 rounded-xl transition-colors shadow-sm shadow-blue-100 whitespace-nowrap">
                    Filter Riwayat
                </button>
                @if(request()->has('tanggal'))
                    <a href="{{ route('tracking') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm px-4 py-3 rounded-xl transition-colors flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($order)
        <div class="bg-white rounded-2xl shadow-md border border-blue-100 p-6 mb-8 ring-4 ring-blue-50/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-4 mb-5 gap-3">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md">Pelacakan Aktif</span>
                    <h2 class="text-lg font-bold text-slate-900 mt-2">Order #{{ $order->id }} <span class="text-slate-400 font-normal">({{ $order->kode_order ?? 'No Code' }})</span></h2>
                </div>
                <div class="text-sm sm:text-right">
                    <p class="text-slate-400 text-xs">Total Budget</p>
                    <p class="font-extrabold text-slate-900 text-lg">Rp {{ number_format($order->budget, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Detail Barang</p>
                    <p class="font-bold text-slate-800 text-sm">{{ $order->nama_barang ?? $order->judul ?? 'Pesanan Jastip' }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Kategori: {{ ucfirst($order->kategori) }}</p>
                    <p class="text-xs text-slate-600 mt-3 bg-white p-2.5 rounded-lg border border-slate-200/60 leading-relaxed">📍 <b>Alamat Antar:</b> {{ $order->lokasi_antar }}</p>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex flex-col justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Jastiper Assigned</p>
                        @if($order->jastiper)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($order->jastiper->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">{{ $order->jastiper->name }}</p>
                                    <p class="text-xs text-amber-600 flex items-center gap-1 font-medium">⭐ {{ number_format($order->jastiper->rating ?? 5, 1) }} (Kurir JastipKu)</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm font-medium text-slate-400 italic py-2">Menunggu konfirmasi kurir jastiper...</p>
                        @endif
                    </div>

                    @if($order->jastiper)
                        <a href="{{ route('chat.show', $order->id) }}" class="mt-4 inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-3 rounded-xl transition-colors shadow-sm">
                            💬 Chat Live ke Jastiper
                        </a>
                    @endif
                </div>
            </div>

            <div class="border-t border-slate-100 pt-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Progres Pengiriman</p>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
                    <div class="p-3 rounded-xl border {{ $order->status == 'pending' ? 'bg-amber-50 border-amber-200 text-amber-700 font-bold' : 'bg-slate-50 border-slate-100 text-slate-400' }} text-xs">⏳ Menunggu</div>
                    <div class="p-3 rounded-xl border {{ $order->status == 'proses' ? 'bg-blue-50 border-blue-200 text-blue-700 font-bold' : 'bg-slate-50 border-slate-100 text-slate-400' }} text-xs">👨‍🍳 Diproses</div>
                    <div class="p-3 rounded-xl border {{ $order->status == 'otw' ? 'bg-purple-50 border-purple-200 text-purple-700 font-bold' : 'bg-slate-50 border-slate-100 text-slate-400' }} text-xs">🏍️ Diantar</div>
                    <div class="p-3 rounded-xl border {{ $order->status == 'selesai' ? 'bg-emerald-50 border-emerald-200 text-emerald-700 font-bold' : 'bg-slate-50 border-slate-100 text-slate-400' }} text-xs">✅ Selesai</div>
                    <div class="p-3 rounded-xl border {{ $order->status == 'batal' ? 'bg-rose-50 border-rose-200 text-rose-700 font-bold' : 'bg-slate-50 border-slate-100 text-slate-400' }} text-xs">❌ Batal</div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="mb-5">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">📦 Riwayat Pesanan Kamu</h2>
            <p class="text-xs text-slate-400 mt-0.5">Silakan klik baris mana saja untuk melihat visual pelacakan kotak besar di atas.</p>
        </div>

        @if($orders->isEmpty())
            <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <p class="text-sm font-medium text-slate-500">Tidak ada data pesanan pada filter parameter ini.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-100">
                            <th class="p-4">ID Order</th>
                            <th class="p-4">Nama Barang</th>
                            <th class="p-4">Budget</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-slate-700">
                        @foreach($orders as $item)
                            <tr class="hover:bg-blue-50/50 cursor-pointer transition-colors {{ request('kode') == $item->id ? 'bg-blue-50/30 font-semibold border-l-4 border-blue-500' : '' }}"
                                onclick="window.location.href='{{ route('tracking', array_merge(request()->query(), ['kode' => $item->id])) }}'">
                                <td class="p-4 font-mono font-bold text-slate-900 text-xs">#{{ $item->id }}</td>
                                <td class="p-4">
                                    <div class="text-slate-800 font-medium">{{ $item->nama_barang ?? $item->judul ?? 'Pesanan Jastip' }}</div>
                                    <div class="text-slate-400 text-xs mt-0.5">{{ $item->created_at->format('d M Y') }} - {{ ucfirst($item->kategori) }}</div>
                                </td>
                                <td class="p-4 text-slate-900 font-semibold">Rp {{ number_format($item->budget, 0, ',', '.') }}</td>
                                <td class="p-4">
                                    @if($item->status == 'pending')
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-amber-50 text-amber-700 border border-amber-100">Menunggu</span>
                                    @elseif($item->status == 'proses')
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-50 text-blue-700 border border-blue-100">Diproses</span>
                                    @elseif($item->status == 'otw')
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-purple-50 text-purple-700 border border-purple-100">Diantar</span>
                                    @elseif($item->status == 'selesai')
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Selesai</span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-slate-100 text-slate-600">Batal</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <span class="text-xs bg-white border border-slate-200 px-3 py-1.5 rounded-lg text-blue-600 font-semibold hover:border-blue-400 transition-all shadow-sm">
                                        🔎 Lacak
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection