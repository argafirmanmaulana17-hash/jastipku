{{-- resources/views/dashboard/jastiper-earnings.blade.php --}}
@extends('layouts.app')
@section('title', 'Penghasilan - JastipKu')
@section('content')
<div class="pt-16 bg-slate-50 min-h-screen">
    <div class="flex">
        <aside class="w-64 bg-white border-r border-slate-200 fixed left-0 top-16 hidden lg:block" style="min-height:calc(100vh - 64px)">
            <div class="p-6"><nav class="space-y-1">
                @foreach([['href'=>'/dashboard/jastiper','icon'=>'📊','label'=>'Overview'],['href'=>'/dashboard/jastiper/orders','icon'=>'📦','label'=>'Order Masuk'],['href'=>'/dashboard/jastiper/history','icon'=>'📋','label'=>'Riwayat Order'],['href'=>'/dashboard/jastiper/earnings','icon'=>'💰','label'=>'Penghasilan'],['href'=>'/dashboard/jastiper/profile','icon'=>'👤','label'=>'Profil Saya']] as $menu)
                <a href="{{ $menu['href'] }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors {{ request()->is(ltrim($menu['href'],'/')) ? 'active' : '' }}">
                    <span>{{ $menu['icon'] }}</span> {{ $menu['label'] }}
                </a>
                @endforeach
            </nav></div>
        </aside>
        <main class="flex-1 lg:ml-64 p-6 lg:p-8">
            <h1 class="font-display text-2xl font-800 text-slate-900 mb-8">💰 Penghasilan Saya</h1>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="text-2xl mb-2">💰</div>
                    <div class="font-display text-2xl font-700 text-blue-600">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                    <div class="text-sm text-slate-500 mt-1">Total Penghasilan</div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="text-2xl mb-2">📦</div>
                    <div class="font-display text-2xl font-700 text-slate-900">{{ $orders->count() }}</div>
                    <div class="text-sm text-slate-500 mt-1">Order Selesai</div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="text-2xl mb-2">📊</div>
                    <div class="font-display text-2xl font-700 text-slate-900">
                        Rp {{ $orders->count() > 0 ? number_format($totalEarnings / $orders->count(), 0, ',', '.') : '0' }}
                    </div>
                    <div class="text-sm text-slate-500 mt-1">Rata-rata per Order</div>
                </div>
            </div>

            <!-- Earnings List -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="font-display text-lg font-700 text-slate-800">Riwayat Penghasilan</h2>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($orders as $order)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <div class="font-mono text-sm font-semibold text-blue-600">{{ $order->kode_order }}</div>
                            <div class="text-sm text-slate-600">{{ $order->nama }} · {{ ucfirst($order->kategori) }}</div>
                            <div class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-green-600">+ Rp {{ number_format($order->budget, 0, ',', '.') }}</div>
                            <div class="text-xs text-slate-400">{{ ucfirst($order->pembayaran) }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center text-slate-400 text-sm">Belum ada penghasilan</div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
