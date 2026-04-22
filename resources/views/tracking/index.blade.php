@extends('layouts.app')
@section('title', 'Tracking Order - JastipKu')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-10">
            <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">Lacak Pesanan</span>
            <h1 class="font-display text-4xl font-800 text-slate-900 mt-2">Tracking Order</h1>
            <p class="text-slate-500 mt-3">Masukkan kode order kamu untuk melihat status terkini.</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 mb-6">
            <form action="/tracking" method="GET" class="flex gap-3">
                <input type="text" name="kode" value="{{ request('kode') }}" placeholder="Masukkan kode order (contoh: JK-2024-001)"
                    class="flex-1 px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
            </form>
        </div>

        @if(isset($order))
        <!-- Order Found -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 animate-fadeInUp">

            <!-- Order Header -->
            <div class="flex items-start justify-between mb-6 pb-6 border-b border-slate-100">
                <div>
                    <div class="text-xs text-slate-500 mb-1">Kode Order</div>
                    <div class="font-display text-xl font-700 text-slate-900">{{ $order->kode_order }}</div>
                    <div class="text-sm text-slate-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($order->status == 'pending') badge-pending
                    @elseif($order->status == 'proses') badge-proses
                    @elseif($order->status == 'otw') badge-otw
                    @elseif($order->status == 'selesai') badge-selesai
                    @else badge-batal @endif">
                    @if($order->status == 'pending') ⏳ Menunggu
                    @elseif($order->status == 'proses') 🔄 Diproses
                    @elseif($order->status == 'otw') 🛵 OTW
                    @elseif($order->status == 'selesai') ✅ Selesai
                    @else ❌ Dibatal @endif
                </span>
            </div>

            <!-- Progress Timeline -->
            <div class="mb-8">
                <h3 class="font-semibold text-slate-700 mb-4">Status Perjalanan</h3>
                <div class="space-y-0">
                    @foreach([
                        ['key'=>'pending','label'=>'Order Diterima','desc'=>'Order kamu sudah masuk ke sistem kami','icon'=>'📋'],
                        ['key'=>'proses','label'=>'Sedang Diproses','desc'=>'Jastiper sedang menuju lokasi pengambilan','icon'=>'🔄'],
                        ['key'=>'otw','label'=>'OTW ke Kamu','desc'=>'Jastiper sedang dalam perjalanan ke lokasimu','icon'=>'🛵'],
                        ['key'=>'selesai','label'=>'Order Selesai','desc'=>'Barang sudah diterima. Terima kasih!','icon'=>'✅'],
                    ] as $i => $step)
                    @php
                        $statuses = ['pending','proses','otw','selesai'];
                        $currentIndex = array_search($order->status, $statuses);
                        $stepIndex = array_search($step['key'], $statuses);
                        $isDone = $stepIndex <= $currentIndex;
                        $isCurrent = $stepIndex == $currentIndex;
                    @endphp
                    <div class="flex gap-4 {{ !$loop->last ? 'pb-6' : '' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg transition-all
                                {{ $isDone ? 'bg-blue-600 shadow-lg shadow-blue-200' : 'bg-slate-100' }}
                                {{ $isCurrent ? 'ring-4 ring-blue-200' : '' }}">
                                {{ $isDone ? $step['icon'] : '○' }}
                            </div>
                            @if(!$loop->last)
                            <div class="w-0.5 flex-1 mt-1 {{ $isDone ? 'bg-blue-300' : 'bg-slate-200' }}"></div>
                            @endif
                        </div>
                        <div class="pb-2 flex-1">
                            <div class="font-semibold text-sm {{ $isDone ? 'text-slate-800' : 'text-slate-400' }}">{{ $step['label'] }}</div>
                            <div class="text-xs {{ $isDone ? 'text-slate-500' : 'text-slate-300' }} mt-0.5">{{ $step['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Details -->
            <div class="bg-slate-50 rounded-2xl p-5 mb-6">
                <h3 class="font-semibold text-slate-700 mb-4">Detail Pesanan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Nama</span>
                        <span class="font-medium text-slate-800">{{ $order->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Kategori</span>
                        <span class="font-medium text-slate-800">{{ ucfirst($order->kategori) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Ambil dari</span>
                        <span class="font-medium text-slate-800">{{ $order->lokasi_ambil }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Antar ke</span>
                        <span class="font-medium text-slate-800">{{ $order->lokasi_antar }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Pembayaran</span>
                        <span class="font-medium text-slate-800">{{ strtoupper($order->pembayaran) }}</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-slate-200">
                        <span class="text-slate-500">Budget</span>
                        <span class="font-bold text-blue-600">Rp {{ number_format($order->budget, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($order->jastiper)
            <!-- Jastiper Info -->
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
                <h3 class="font-semibold text-slate-700 mb-3">Jastiper Kamu</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold">
                        {{ substr($order->jastiper->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">{{ $order->jastiper->name }}</div>
                        <div class="text-sm text-slate-500">⭐ {{ $order->jastiper->rating ?? '5.0' }} · {{ $order->jastiper->total_order ?? '0' }} order selesai</div>
                    </div>
                    <a href="https://wa.me/62{{ ltrim($order->jastiper->whatsapp, '0') }}" target="_blank"
                        class="ml-auto bg-green-500 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-green-600 transition-colors">
                        💬 WA
                    </a>
                </div>
            </div>
            @endif
        </div>

        @elseif(request('kode'))
        <!-- Order Not Found -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-12 text-center">
            <div class="text-5xl mb-4">🔍</div>
            <h3 class="font-display text-xl font-700 text-slate-800 mb-2">Order Tidak Ditemukan</h3>
            <p class="text-slate-500 text-sm">Kode order <strong>{{ request('kode') }}</strong> tidak ada di sistem kami. Pastikan kode sudah benar.</p>
        </div>

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-12 text-center">
            <div class="text-5xl mb-4 animate-float inline-block">📦</div>
            <h3 class="font-display text-xl font-700 text-slate-800 mb-2">Cek Status Ordermu</h3>
            <p class="text-slate-500 text-sm mb-6">Kode order dikirim ke WhatsApp kamu setelah order dikonfirmasi.</p>
            <a href="/order" class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 transition-colors">
                Buat Order Baru
            </a>
        </div>
        @endif

    </div>
</div>
@endsection
