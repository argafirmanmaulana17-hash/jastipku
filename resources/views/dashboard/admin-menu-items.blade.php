@extends('layouts.app')

@section('title', 'Menu Makanan & Minuman - JastipKu')

@section('content')
    <div class="pt-16 bg-slate-50 min-h-screen">
        <div class="flex">

            <!-- SIDEBAR -->
            <aside class="w-64 bg-white border-r border-slate-200 fixed left-0 top-16 hidden lg:block"
                style="min-height: calc(100vh - 64px)">
                <div class="p-6">
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">
                        Menu Admin
                    </div>

                    <nav class="space-y-1">
                        @foreach ([['href' => '/dashboard/admin', 'icon' => '📊', 'label' => 'Overview'], ['href' => '/dashboard/admin/orders', 'icon' => '📦', 'label' => 'Semua Order'], ['href' => '/dashboard/admin/jastipers', 'icon' => '🛵', 'label' => 'Kelola Jastiper'], ['href' => '/dashboard/admin/users', 'icon' => '👥', 'label' => 'Kelola Pembeli'], ['href' => '/dashboard/admin/menu-items', 'icon' => '🍽️', 'label' => 'Menu Makanan'], ['href' => '/dashboard/admin/reports', 'icon' => '📈', 'label' => 'Laporan'], ['href' => '/dashboard/admin/settings', 'icon' => '⚙️', 'label' => 'Pengaturan']] as $menu)
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

                <!-- HEADER -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
                    <div>
                        <h1 class="font-display text-2xl font-800 text-slate-900">
                            Menu Makanan & Minuman
                        </h1>
                        <p class="text-slate-500 text-sm mt-1">
                            Kelola price list makanan/minuman yang bisa dipilih pembeli.
                        </p>
                    </div>

                    <a href="{{ route('dashboard.admin.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                        ← Kembali
                    </a>
                </div>

                <!-- ALERT -->
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

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 text-sm">
                        <div class="font-bold mb-1">Ada yang perlu diperbaiki:</div>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- FORM TAMBAH / EDIT -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 h-fit">
                        <h2 class="font-display text-lg font-700 text-slate-900 mb-2">
                            {{ $editMenuItem ? 'Edit Menu' : 'Tambah Menu' }}
                        </h2>

                        <p class="text-sm text-slate-500 mb-5">
                            @if ($editMenuItem)
                                Perubahan menu hanya berlaku untuk order baru. Riwayat order lama tetap memakai snapshot
                                saat transaksi.
                            @else
                                Tambahkan makanan/minuman baru ke price list.
                            @endif
                        </p>

                        <form method="POST"
                            action="{{ $editMenuItem ? route('dashboard.admin.menu-items.update', $editMenuItem->id) : route('dashboard.admin.menu-items.store') }}"
                            class="space-y-4">
                            @csrf

                            @if ($editMenuItem)
                                @method('PATCH')
                            @endif

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Menu *</label>
                                <input type="text" name="nama" value="{{ old('nama', $editMenuItem->nama ?? '') }}"
                                    placeholder="Contoh: Es Teh"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Toko/Kantin</label>
                                <input type="text" name="toko" value="{{ old('toko', $editMenuItem->toko ?? '') }}"
                                    placeholder="Contoh: Kantin Kampus"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori *</label>
                                <select name="kategori"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none">
                                    <option value="makanan"
                                        {{ old('kategori', $editMenuItem->kategori ?? 'makanan') == 'makanan' ? 'selected' : '' }}>
                                        Makanan
                                    </option>
                                    <option value="minuman"
                                        {{ old('kategori', $editMenuItem->kategori ?? '') == 'minuman' ? 'selected' : '' }}>
                                        Minuman
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Harga *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                                    <input type="number" name="harga"
                                        value="{{ old('harga', $editMenuItem->harga ?? '') }}" placeholder="3000"
                                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none">
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">
                                {{ $editMenuItem ? 'Simpan Perubahan' : '+ Tambah Menu' }}
                            </button>

                            @if ($editMenuItem)
                                <a href="{{ route('dashboard.admin.menu-items') }}"
                                    class="block text-center w-full bg-slate-100 text-slate-600 font-semibold py-3 rounded-xl hover:bg-slate-200 transition">
                                    Batal Edit
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- DAFTAR MENU -->
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h2 class="font-display text-lg font-700 text-slate-900">
                                    Daftar Menu
                                </h2>
                                <p class="text-sm text-slate-500 mt-1">
                                    Menu aktif akan muncul di form order kategori makanan/minuman.
                                </p>
                            </div>

                            <div class="text-sm font-bold text-slate-700">
                                {{ $menuItems->total() }} menu
                            </div>
                        </div>

                        @if ($menuItems->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-50 border-b border-slate-100">
                                        <tr class="text-left text-slate-500">
                                            <th class="px-6 py-4 font-semibold">Menu</th>
                                            <th class="px-6 py-4 font-semibold">Kategori</th>
                                            <th class="px-6 py-4 font-semibold">Harga</th>
                                            <th class="px-6 py-4 font-semibold">Status</th>
                                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($menuItems as $menu)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-6 py-4">
                                                    <div class="font-bold text-slate-900">
                                                        {{ $menu->nama }}
                                                    </div>
                                                    <div class="text-xs text-slate-400">
                                                        {{ $menu->toko ?: 'Toko belum diisi' }}
                                                    </div>
                                                </td>

                                                <td class="px-6 py-4 text-slate-600">
                                                    {{ ucfirst($menu->kategori) }}
                                                </td>

                                                <td class="px-6 py-4 font-bold text-slate-900">
                                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    @if ($menu->aktif)
                                                        <span
                                                            class="px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-100 text-xs font-bold">
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-3 py-1 rounded-full bg-slate-50 text-slate-500 border border-slate-100 text-xs font-bold">
                                                            Nonaktif
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="px-6 py-4">
                                                    <div class="flex justify-end gap-2">
                                                        <a href="{{ route('dashboard.admin.menu-items.edit', $menu->id) }}"
                                                            class="px-3 py-2 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100">
                                                            Edit
                                                        </a>

                                                        <form method="POST"
                                                            action="{{ route('dashboard.admin.menu-items.toggle', $menu->id) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                class="px-3 py-2 rounded-lg text-xs font-semibold border border-slate-200 hover:bg-slate-50">
                                                                {{ $menu->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>

                                                        <form method="POST"
                                                            action="{{ route('dashboard.admin.menu-items.delete', $menu->id) }}"
                                                            onsubmit="return confirm('Hapus menu ini? Untuk data yang sudah pernah dipakai order, lebih aman nonaktifkan saja.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="px-3 py-2 rounded-lg text-xs font-semibold bg-red-50 text-red-600 border border-red-100 hover:bg-red-100">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="px-6 py-4 border-t border-slate-100">
                                {{ $menuItems->links() }}
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="text-5xl mb-3">🍽️</div>
                                <div class="font-bold text-slate-700">
                                    Belum ada menu
                                </div>
                                <div class="text-sm text-slate-400 mt-1">
                                    Tambahkan makanan/minuman pertama dari form di sebelah kiri.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
