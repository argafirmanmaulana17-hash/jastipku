@extends('layouts.app')
@section('title', 'Kelola Jastiper - Admin JastipKu')

@section('content')
<div class="pt-16 bg-slate-50 min-h-screen">
    <div class="flex">
        <aside class="w-64 bg-white border-r border-slate-200 fixed left-0 top-16 hidden lg:block" style="min-height:calc(100vh - 64px)">
            <div class="p-6">
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Menu Admin</div>
                <nav class="space-y-1">
                    @foreach([
                        ['href'=>'/dashboard/admin','icon'=>'📊','label'=>'Overview'],
                        ['href'=>'/dashboard/admin/orders','icon'=>'📦','label'=>'Semua Order'],
                        ['href'=>'/dashboard/admin/jastipers','icon'=>'🛵','label'=>'Kelola Jastiper'],
                        ['href'=>'/dashboard/admin/users','icon'=>'👥','label'=>'Kelola User'],
                        ['href'=>'/dashboard/admin/reports','icon'=>'📈','label'=>'Laporan'],
                    ] as $menu)
                    <a href="{{ $menu['href'] }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors {{ request()->is(ltrim($menu['href'],'/')) ? 'active' : '' }}">
                        <span>{{ $menu['icon'] }}</span> {{ $menu['label'] }}
                    </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <main class="flex-1 lg:ml-64 p-6 lg:p-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="font-display text-2xl font-800 text-slate-900">🛵 Kelola Jastiper</h1>
                <div class="text-sm text-slate-500">Total: {{ $jastipers->total() }} jastiper</div>
            </div>

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($jastipers as $jastiper)
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($jastiper->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800">{{ $jastiper->name }}</div>
                                <div class="text-xs text-slate-500">{{ $jastiper->email }}</div>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            {{ $jastiper->status === 'aktif' ? 'bg-green-100 text-green-700' : ($jastiper->status === 'sibuk' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ ucfirst($jastiper->status) }}
                        </span>
                    </div>

                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Area</span>
                            <span class="text-slate-800 font-medium text-right max-w-[60%]">{{ $jastiper->area_layanan ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Kendaraan</span>
                            <span class="text-slate-800">{{ ucfirst($jastiper->kendaraan ?? '-') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">WhatsApp</span>
                            <span class="text-slate-800">{{ $jastiper->whatsapp }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Rating</span>
                            <span class="text-yellow-500 font-semibold">★ {{ number_format($jastiper->rating, 1) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Total Order</span>
                            <span class="text-slate-800 font-semibold">{{ $jastiper->orders_count ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-slate-100">
                        <form method="POST" action="/dashboard/admin/jastipers/{{ $jastiper->id }}/toggle" class="flex-1">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full text-sm font-semibold py-2 rounded-xl transition-colors
                                {{ $jastiper->status === 'aktif' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                {{ $jastiper->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <a href="https://wa.me/62{{ ltrim($jastiper->whatsapp, '0') }}" target="_blank"
                            class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-sm font-semibold hover:bg-blue-100 transition-colors">
                            WA
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400">
                    Belum ada jastiper terdaftar
                </div>
                @endforelse
            </div>

            @if($jastipers->hasPages())
            <div class="mt-6">{{ $jastipers->links() }}</div>
            @endif
        </main>
    </div>
</div>
@endsection
