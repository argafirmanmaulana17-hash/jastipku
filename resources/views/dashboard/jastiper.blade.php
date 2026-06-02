@extends('layouts.app')
@section('title', 'Dashboard Jastiper - JastipKu')

@section('content')
    <div class="pt-16 bg-slate-50 min-h-screen">
        <div class="flex">

            <!-- SIDEBAR -->
            <aside class="w-64 bg-white border-r border-slate-200 fixed left-0 top-16 hidden lg:block"
                style="min-height: calc(100vh - 64px)">
                <div class="p-6">
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">
                        Menu Jastiper
                    </div>

                    <nav class="space-y-1">
                        @foreach ([['href' => '/dashboard/jastiper', 'icon' => '📊', 'label' => 'Overview'], ['href' => '/dashboard/jastiper/orders', 'icon' => '📦', 'label' => 'Order Masuk'], ['href' => '/dashboard/jastiper/history', 'icon' => '📋', 'label' => 'Riwayat Order'], ['href' => '/dashboard/jastiper/earnings', 'icon' => '💰', 'label' => 'Penghasilan'], ['href' => '/dashboard/jastiper/profile', 'icon' => '👤', 'label' => 'Profil Saya']] as $menu)
                            <a href="{{ $menu['href'] }}"
                                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors {{ request()->is(ltrim($menu['href'], '/')) ? 'active' : '' }}">
                                <span>{{ $menu['icon'] }}</span>
                                {{ $menu['label'] }}
                            </a>
                        @endforeach
                        <a href="{{ route('dashboard.jastiper.bills') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-red-50 text-red-600 text-sm font-bold border border-red-100 hover:bg-red-100">
                            💳 Tagihan Aplikasi
                        </a>
                    </nav>

                    <!-- Status Toggle -->
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">
                            Status Kamu
                        </div>

                        <form method="POST" action="/dashboard/jastiper/toggle-status">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl border-2 transition-all
                            {{ ($jastiper->status ?? 'offline') == 'aktif' ? 'border-green-400 bg-green-50' : 'border-slate-200 bg-white' }}">
                                <span
                                    class="flex items-center gap-2 text-sm font-semibold
                                {{ ($jastiper->status ?? 'offline') == 'aktif' ? 'text-green-700' : 'text-slate-500' }}">
                                    <span
                                        class="w-2 h-2 rounded-full {{ ($jastiper->status ?? 'offline') == 'aktif' ? 'bg-green-500 animate-pulse' : 'bg-slate-400' }}">
                                    </span>
                                    {{ ($jastiper->status ?? 'offline') == 'aktif' ? 'Aktif' : 'Offline' }}
                                </span>
                                <span class="text-xs text-slate-400">Klik untuk ganti</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="flex-1 lg:ml-64 p-6 lg:p-8">

                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="font-display text-2xl font-800 text-slate-900">
                            Halo, {{ Auth::user()->name ?? 'Jastiper' }}! 👋
                        </h1>
                        <p class="text-slate-500 text-sm mt-1">{{ now()->format('l, d F Y') }}</p>
                    </div>
                </div>

                @if (session('success'))
                    <div
                        class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                        ℹ️ {{ session('info') }}
                    </div>
                @endif

                <!-- Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    @foreach ([['label' => 'Order Hari Ini', 'value' => $stats['today'] ?? '0', 'icon' => '📅'], ['label' => 'Total Selesai', 'value' => $stats['total_done'] ?? '0', 'icon' => '✅'], ['label' => 'Rating', 'value' => ($stats['rating'] ?? '5.0') . '★', 'icon' => '⭐'], ['label' => 'Penghasilan Bulan Ini', 'value' => 'Rp ' . number_format($stats['monthly_earn'] ?? 0, 0, ',', '.'), 'icon' => '💰']] as $stat)
                        <div class="bg-white border border-slate-200 rounded-2xl p-5">
                            <div class="text-2xl mb-2">{{ $stat['icon'] }}</div>
                            <div class="font-display text-2xl font-700 text-slate-900">{{ $stat['value'] }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Incoming Orders -->
                <div class="bg-white rounded-2xl border border-slate-200 mb-6">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="font-display text-lg font-700 text-slate-800">
                            🔔 Order Menunggu Konfirmasi
                        </h2>
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse($pendingOrders ?? [] as $order)
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <div class="font-mono text-sm font-semibold text-blue-600">
                                            {{ $order->kode_order }}
                                        </div>
                                        <div class="font-semibold text-slate-800 mt-0.5">
                                            {{ $order->nama }}
                                        </div>
                                    </div>

                                    <span class="text-lg font-bold text-slate-800">
                                        Rp {{ number_format($order->total_bayar ?: $order->budget, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="text-sm text-slate-600 mb-1">
                                    📍 <strong>Ambil:</strong> {{ $order->lokasi_ambil }}
                                </div>

                                <div class="text-sm text-slate-600 mb-3">
                                    🏠 <strong>Antar ke:</strong> {{ $order->lokasi_antar }}
                                </div>

                                <div class="bg-slate-50 rounded-xl p-3 text-sm text-slate-600 mb-4">
                                    {{ \Illuminate\Support\Str::limit($order->detail_pesanan, 100) }}
                                </div>

                                @if ($order->jenis_harga === 'pricelist')
                                    <div
                                        class="bg-green-50 border border-green-200 rounded-xl p-3 text-sm text-green-700 mb-4">
                                        <div class="font-bold mb-1">Harga Price List</div>
                                        <div>Harga barang: Rp
                                            {{ number_format($order->harga_barang ?? $order->budget, 0, ',', '.') }}</div>
                                        <div>Ongkos jastip: Rp
                                            {{ number_format($order->ongkos_jastip ?? 3000, 0, ',', '.') }}</div>
                                        <div class="font-bold">
                                            Total: Rp
                                            {{ number_format($order->total_bayar ?: ($order->harga_barang ?? $order->budget) + 3000, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-sm text-amber-700 mb-4">
                                        <div class="font-bold mb-1">Barang Penawaran</div>
                                        <div>Terima order dulu, lalu ajukan harga dari bagian Order Aktif Saya.</div>
                                    </div>
                                @endif

                                <div class="flex gap-3 mt-4">
                                    <form method="POST" action="/dashboard/jastiper/orders/{{ $order->id }}/accept"
                                        class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-xl hover:bg-blue-700 transition-colors text-sm">
                                            ✅ Terima Order
                                        </button>
                                    </form>

                                    <form method="POST" action="/dashboard/jastiper/orders/{{ $order->id }}/reject"
                                        class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full bg-red-50 text-red-600 font-semibold py-2.5 rounded-xl hover:bg-red-100 transition-colors text-sm border border-red-200">
                                            ❌ Lewati Order
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <div class="text-4xl mb-3">😴</div>
                                <div class="font-semibold text-slate-600">Belum ada order masuk</div>
                                <div class="text-sm text-slate-400 mt-1">Pastikan status kamu aktif ya!</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Active Orders -->
                <div class="bg-white rounded-2xl border border-slate-200">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="font-display text-lg font-700 text-slate-800">
                            🛵 Order Aktif Saya
                        </h2>
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse($activeOrders ?? [] as $order)
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <div class="font-mono text-sm font-semibold text-blue-600">
                                            {{ $order->kode_order }}
                                        </div>
                                        <div class="font-semibold text-slate-800">
                                            {{ $order->nama }}
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            📱 {{ $order->whatsapp }}
                                        </div>
                                    </div>

                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $order->status_label ?? ucfirst($order->status) }}
                                    </span>
                                </div>

                                <div class="text-sm text-slate-600 mb-1">
                                    📍 <strong>Ambil:</strong> {{ $order->lokasi_ambil }}
                                </div>

                                <div class="text-sm text-slate-600 mb-3">
                                    🏠 <strong>Antar:</strong> {{ $order->lokasi_antar }}
                                </div>

                                <div class="bg-slate-50 rounded-xl p-3 text-sm text-slate-600 mb-4">
                                    {{ \Illuminate\Support\Str::limit($order->detail_pesanan, 120) }}
                                </div>

                                @if ($order->status == 'menunggu_harga')
                                    <form method="POST"
                                        action="/dashboard/jastiper/orders/{{ $order->id }}/offer-price"
                                        class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                                        @csrf
                                        @method('PATCH')

                                        <div class="font-bold text-amber-800 mb-3">
                                            Ajukan Harga ke Pembeli
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                            <input type="number" name="harga_barang" required min="1000"
                                                placeholder="Harga barang"
                                                class="w-full px-3 py-2 rounded-lg border border-amber-300 text-sm">

                                            <input type="number" name="ongkos_jastip" required min="1000"
                                                placeholder="Ongkos jastip"
                                                class="w-full px-3 py-2 rounded-lg border border-amber-300 text-sm">
                                        </div>

                                        <textarea name="catatan_harga" rows="2" placeholder="Catatan harga"
                                            class="w-full px-3 py-2 rounded-lg border border-amber-300 text-sm mb-3"></textarea>

                                        <button type="submit"
                                            class="w-full bg-amber-600 text-white font-semibold py-2.5 rounded-xl hover:bg-amber-700 transition-colors text-sm">
                                            💰 Kirim Penawaran Harga
                                        </button>
                                    </form>
                                @endif

                                @if ($order->status == 'menunggu_persetujuan')
                                    <div
                                        class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-sm text-amber-700 mb-4">
                                        Menunggu pembeli menyetujui harga.
                                        Total penawaran:
                                        <strong>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</strong>
                                    </div>
                                @endif

                                @if (in_array($order->status, ['proses', 'otw']))
                                    <div
                                        class="bg-green-50 border border-green-200 rounded-xl p-3 text-sm text-green-700 mb-4">
                                        <div>Harga barang: Rp
                                            {{ number_format($order->harga_barang ?? $order->budget, 0, ',', '.') }}</div>
                                        <div>Ongkos jastip: Rp {{ number_format($order->ongkos_jastip ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="font-bold">Total: Rp
                                            {{ number_format($order->total_bayar ?: $order->budget, 0, ',', '.') }}</div>
                                    </div>
                                @endif

                                <div class="flex flex-wrap gap-2 mt-3">
                                    @if ($order->status == 'proses')
                                        <form method="POST"
                                            action="/dashboard/jastiper/orders/{{ $order->id }}/update-status"
                                            class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="otw">
                                            <button type="submit"
                                                class="w-full bg-purple-600 text-white font-semibold py-2 rounded-xl hover:bg-purple-700 transition-colors text-sm">
                                                🛵 Update: OTW
                                            </button>
                                        </form>
                                    @elseif($order->status == 'otw')
                                        <form method="POST"
                                            action="/dashboard/jastiper/orders/{{ $order->id }}/update-status"
                                            class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit"
                                                class="w-full bg-green-600 text-white font-semibold py-2 rounded-xl hover:bg-green-700 transition-colors text-sm">
                                                ✅ Tandai Selesai
                                            </button>
                                        </form>
                                    @endif

                                    <a href="https://wa.me/62{{ ltrim($order->whatsapp, '0') }}" target="_blank"
                                        class="bg-green-50 text-green-600 font-semibold px-4 py-2 rounded-xl hover:bg-green-100 transition-colors text-sm border border-green-200">
                                        💬 WA
                                    </a>

                                    <a href="{{ route('chat.show', $order->id) }}"
                                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700 transition-colors text-sm border border-blue-200">
                                        💬 Chat Pembeli
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-slate-400 text-sm">
                                Tidak ada order aktif saat ini
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
