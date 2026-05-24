@extends('layouts.app')
@section('title', 'Dashboard Admin - JastipKu')

@section('styles')
<style>
.sidebar {
    min-height: calc(100vh - 64px);
}

.stat-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
}
</style>
@endsection

@section('content')
<div class="pt-16 bg-slate-50 min-h-screen">
    <div class="flex">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r border-slate-200 sidebar fixed left-0 top-16 hidden lg:block">
            <div class="p-6">
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">
                    Menu Admin
                </div>

                <nav class="space-y-1">
                    @foreach([
                        ['href' => '/dashboard/admin', 'icon' => '📊', 'label' => 'Overview'],
                        ['href' => '/dashboard/admin/orders', 'icon' => '📦', 'label' => 'Semua Order'],
                        ['href' => '/dashboard/admin/jastipers', 'icon' => '🛵', 'label' => 'Kelola Jastiper'],
                        ['href' => '/dashboard/admin/users', 'icon' => '👥', 'label' => 'Kelola Pembeli'],
                        ['href' => '/dashboard/admin/menu-items', 'icon' => '🍽️', 'label' => 'Menu Makanan'],
                        ['href' => '/dashboard/admin/reports', 'icon' => '📈', 'label' => 'Laporan'],
                        ['href' => '/dashboard/admin/settings', 'icon' => '⚙️', 'label' => 'Pengaturan'],
                    ] as $menu)
                        <a href="{{ $menu['href'] }}"
                           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors {{ request()->is(ltrim($menu['href'], '/')) ? 'active' : '' }}">
                            <span>{{ $menu['icon'] }}</span>
                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 lg:ml-64 p-6 lg:p-8">

            <!-- Page Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="font-display text-2xl font-800 text-slate-900">
                        Dashboard Admin
                    </h1>
                    <p class="text-slate-500 text-sm mt-1">
                        {{ now()->format('l, d F Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>

                    <div class="hidden sm:block">
                        <div class="text-sm font-semibold text-slate-800">
                            {{ Auth::user()->name ?? 'Admin' }}
                        </div>
                        <div class="text-xs text-slate-500">
                            Administrator
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                @foreach([
                    ['label' => 'Total Order', 'value' => $stats['total_orders'] ?? '0', 'icon' => '📦', 'change' => '+12%'],
                    ['label' => 'Order Hari Ini', 'value' => $stats['today_orders'] ?? '0', 'icon' => '📅', 'change' => '+5'],
                    ['label' => 'Jastiper Aktif', 'value' => $stats['active_jastipers'] ?? '0', 'icon' => '🛵', 'change' => 'Online'],
                    ['label' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($stats['total_revenue'] ?? 0, 0, ',', '.'), 'icon' => '💰', 'change' => '+20%'],
                ] as $stat)
                    <div class="stat-card">
                        <div class="flex items-start justify-between mb-3">
                            <div class="text-2xl">{{ $stat['icon'] }}</div>
                            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                {{ $stat['change'] }}
                            </span>
                        </div>

                        <div class="font-display text-2xl font-700 text-slate-900 mb-1">
                            {{ $stat['value'] }}
                        </div>

                        <div class="text-xs text-slate-500">
                            {{ $stat['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Menu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <a href="/dashboard/admin/orders"
                   class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-300 hover:shadow-md transition">
                    <div class="text-3xl mb-3">📦</div>
                    <div class="font-bold text-slate-900">Semua Order</div>
                    <div class="text-sm text-slate-500 mt-1">
                        Pantau semua transaksi jastip.
                    </div>
                </a>

                <a href="/dashboard/admin/jastipers"
                   class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-300 hover:shadow-md transition">
                    <div class="text-3xl mb-3">🛵</div>
                    <div class="font-bold text-slate-900">Kelola Jastiper</div>
                    <div class="text-sm text-slate-500 mt-1">
                        Lihat dan atur akun jastiper.
                    </div>
                </a>

                <a href="{{ route('dashboard.admin.users') }}"
                   class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-300 hover:shadow-md transition">
                    <div class="text-3xl mb-3">👥</div>
                    <div class="font-bold text-slate-900">Kelola Pembeli</div>
                    <div class="text-sm text-slate-500 mt-1">
                        Lihat data akun pembeli/customer.
                    </div>
                </a>

                <a href="/dashboard/admin/menu-items"
                   class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-300 hover:shadow-md transition">
                    <div class="text-3xl mb-3">🍽️</div>
                    <div class="font-bold text-slate-900">Menu Makanan</div>
                    <div class="text-sm text-slate-500 mt-1">
                        Tambah makanan/minuman price list.
                    </div>
                </a>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-2xl border border-slate-200 mb-6">
                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <h2 class="font-display text-lg font-700 text-slate-800">
                        Order Terbaru
                    </h2>
                    <a href="/dashboard/admin/orders" class="text-sm text-blue-600 font-semibold hover:underline">
                        Lihat Semua →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-3">Kode Order</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-3">Pemesan</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-3">Kategori</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-3">Total</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-3">Status</th>
                                <th class="text-left text-xs font-semibold text-slate-500 px-6 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentOrders ?? [] as $order)
                                @php
                                    switch ($order->status) {
                                        case 'pending':
                                            $statusClass = 'badge-pending';
                                            break;
                                        case 'menunggu_harga':
                                            $statusClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                                            break;
                                        case 'menunggu_persetujuan':
                                            $statusClass = 'bg-yellow-50 text-yellow-700 border border-yellow-100';
                                            break;
                                        case 'proses':
                                            $statusClass = 'badge-proses';
                                            break;
                                        case 'otw':
                                            $statusClass = 'badge-otw';
                                            break;
                                        case 'selesai':
                                            $statusClass = 'badge-selesai';
                                            break;
                                        default:
                                            $statusClass = 'badge-batal';
                                            break;
                                    }
                                @endphp

                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono font-semibold text-blue-600">
                                        {{ $order->kode_order }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-800">
                                        {{ $order->nama }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ ucfirst($order->kategori) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm font-semibold text-slate-800">
                                        Rp {{ number_format($order->total_bayar ?: $order->budget, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                            {{ $order->status_label ?? ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="/dashboard/admin/orders/{{ $order->id }}"
                                               class="text-xs font-semibold text-blue-600 hover:underline">
                                                Detail
                                            </a>

                                            <form method="POST" action="/dashboard/admin/orders/{{ $order->id }}/update-status" class="inline">
                                                @csrf
                                                @method('PATCH')

                                                <select name="status"
                                                        onchange="this.form.submit()"
                                                        class="text-xs border border-slate-200 rounded-lg px-2 py-1 bg-white">
                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="menunggu_harga" {{ $order->status == 'menunggu_harga' ? 'selected' : '' }}>Menunggu Harga</option>
                                                    <option value="menunggu_persetujuan" {{ $order->status == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                                    <option value="proses" {{ $order->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                                    <option value="otw" {{ $order->status == 'otw' ? 'selected' : '' }}>OTW</option>
                                                    <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="batal" {{ $order->status == 'batal' ? 'selected' : '' }}>Batal</option>
                                                </select>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                        Belum ada order masuk
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Jastipers -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <h2 class="font-display text-lg font-700 text-slate-800">
                        Jastiper Aktif
                    </h2>
                    <a href="/dashboard/admin/jastipers" class="text-sm text-blue-600 font-semibold hover:underline">
                        Kelola →
                    </a>
                </div>

                <div class="divide-y divide-slate-50">
                    @forelse($activeJastipers ?? [] as $jastiper)
                        <div class="flex items-center justify-between px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold">
                                    {{ substr($jastiper->name, 0, 1) }}
                                </div>

                                <div>
                                    <div class="text-sm font-semibold text-slate-800">
                                        {{ $jastiper->name }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $jastiper->area_layanan ?? 'Area belum diatur' }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <div class="text-sm font-semibold text-slate-800">
                                        {{ $jastiper->orders_count ?? 0 }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Order
                                    </div>
                                </div>

                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Aktif
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-slate-400 text-sm">
                            Belum ada jastiper aktif
                        </div>
                    @endforelse
                </div>
            </div>

        </main>
    </div>
</div>
@endsection