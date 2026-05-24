@extends('layouts.app')
@section('title', 'Profil Saya - JastipKu')
@section('content')
    <div class="pt-16 bg-slate-50 min-h-screen">
        <div class="flex">
            <aside class="w-64 bg-white border-r border-slate-200 fixed left-0 top-16 hidden lg:block"
                style="min-height:calc(100vh - 64px)">
                <div class="p-6">
                    <nav class="space-y-1">
                        @foreach ([['href' => '/dashboard/jastiper', 'icon' => '📊', 'label' => 'Overview'], ['href' => '/dashboard/jastiper/orders', 'icon' => '📦', 'label' => 'Order Masuk'], ['href' => '/dashboard/jastiper/history', 'icon' => '📋', 'label' => 'Riwayat Order'], ['href' => '/dashboard/jastiper/earnings', 'icon' => '💰', 'label' => 'Penghasilan'], ['href' => '/dashboard/jastiper/profile', 'icon' => '👤', 'label' => 'Profil Saya']] as $menu)
                            <a href="{{ $menu['href'] }}"
                                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors {{ request()->is(ltrim($menu['href'], '/')) ? 'active' : '' }}">
                                <span>{{ $menu['icon'] }}</span> {{ $menu['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <main class="flex-1 lg:ml-64 p-6 lg:p-8 max-w-2xl">
                <h1 class="font-display text-2xl font-800 text-slate-900 mb-8">👤 Profil Saya</h1>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 text-sm text-green-700">
                        {{ session('success') }}</div>
                @endif

                <!-- Profile Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8 mb-6">
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-display text-xl font-700 text-slate-900">{{ $user->name }}</div>
                            <div class="text-slate-500 text-sm">{{ $user->email }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-yellow-500 text-sm">★ {{ number_format($user->rating, 1) }}</span>
                                <span class="text-slate-400 text-xs">·</span>
                                <span class="text-slate-500 text-sm">{{ $user->total_order }} order selesai</span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="/dashboard/jastiper/profile" class="space-y-5">
                        @csrf @method('PATCH')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ $user->name }}"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">No. WhatsApp</label>
                                <input type="text" name="whatsapp" value="{{ $user->whatsapp }}"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Area Layanan</label>
                                <input type="text" name="area_layanan" value="{{ $user->area_layanan }}"
                                    placeholder="Contoh: Kantin, Koperasi, Mall xxx"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Kendaraan</label>
                                <select name="kendaraan"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm bg-white">
                                    <option value="motor" {{ $user->kendaraan == 'motor' ? 'selected' : '' }}>🛵 Motor
                                    </option>
                                    <option value="sepeda" {{ $user->kendaraan == 'sepeda' ? 'selected' : '' }}>🚲 Sepeda
                                    </option>
                                    <option value="jalan" {{ $user->kendaraan == 'jalan' ? 'selected' : '' }}>🚶 Jalan
                                        Kaki</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition-colors">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8">
                    <h2 class="font-display text-lg font-700 text-slate-800 mb-5">🔒 Ganti Password</h2>
                    <form method="POST" action="/dashboard/jastiper/profile/password" class="space-y-4">
                        @csrf @method('PATCH')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Password Lama</label>
                            <input type="password" name="current_password"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                        </div>
                        <button type="submit"
                            class="w-full bg-slate-800 text-white font-semibold py-3 rounded-xl hover:bg-slate-900 transition-colors">
                            Ganti Password
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </div>
@endsection
