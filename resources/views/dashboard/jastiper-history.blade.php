{{-- resources/views/dashboard/jastiper-history.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Order - JastipKu')
@section('content')
<div class="pt-16 bg-slate-50 min-h-screen">
    <div class="flex">
        <aside class="w-64 bg-white border-r border-slate-200 fixed left-0 top-16 hidden lg:block" style="min-height:calc(100vh - 64px)">
            <div class="p-6">
                <nav class="space-y-1">
                    @foreach([
                        ['href'=>'/dashboard/jastiper','icon'=>'📊','label'=>'Overview'],
                        ['href'=>'/dashboard/jastiper/orders','icon'=>'📦','label'=>'Order Masuk'],
                        ['href'=>'/dashboard/jastiper/history','icon'=>'📋','label'=>'Riwayat Order'],
                        ['href'=>'/dashboard/jastiper/earnings','icon'=>'💰','label'=>'Penghasilan'],
                        ['href'=>'/dashboard/jastiper/profile','icon'=>'👤','label'=>'Profil Saya'],
                    ] as $menu)
                    <a href="{{ $menu['href'] }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors {{ request()->is(ltrim($menu['href'],'/')) ? 'active' : '' }}">
                        <span>{{ $menu['icon'] }}</span> {{ $menu['label'] }}
                    </a>
                    @endforeach
                </nav>
            </div>
        </aside>
        <main class="flex-1 lg:ml-64 p-6 lg:p-8">
            <h1 class="font-display text-2xl font-800 text-slate-900 mb-8">📋 Riwayat Order Saya</h1>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-4">Kode</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-4">Pemesan</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-4">Detail</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-4">Budget</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-4">Status</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-4">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($orders as $order)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-sm font-mono font-semibold text-blue-600">{{ $order->kode_order }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-800">{{ $order->nama }}</div>
                                    <div class="text-xs text-slate-500">{{ $order->whatsapp }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 max-w-[200px] truncate">{{ $order->detail_pesanan }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800">Rp {{ number_format($order->budget,0,',','.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($order->status=='pending') badge-pending
                                        @elseif($order->status=='proses') badge-proses
                                        @elseif($order->status=='otw') badge-otw
                                        @elseif($order->status=='selesai') badge-selesai
                                        @else badge-batal @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">Belum ada riwayat order</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">{{ $orders->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
