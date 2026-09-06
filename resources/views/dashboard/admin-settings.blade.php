@extends('layouts.app')
@section('title', 'Pengaturan Website - Admin')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">⚙️ Pengaturan Website</h1>
            <p class="text-sm text-slate-500 mt-1">Atur jam buka-tutup JastipKu atau matikan website secara darurat.</p>
        </div>

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 font-medium text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <form action="{{ route('dashboard.admin.settings.update') }}" method="POST">
                @csrf

                <!-- SAKLAR MANUAL -->
                <div class="mb-8 bg-rose-50 border border-rose-100 rounded-xl p-5">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_manual_close"
                            class="w-6 h-6 rounded text-rose-600 focus:ring-rose-500"
                            {{ ($settings['is_manual_close'] ?? '0') == '1' ? 'checked' : '' }}>
                        <div>
                            <div class="font-bold text-rose-900">Matikan Website Sementara (Maintenance Mode)</div>
                            <div class="text-xs text-rose-600 mt-0.5">Centang untuk menutup akses JastipKu ke semua customer
                                dan jastiper secara paksa.</div>
                        </div>
                    </label>
                </div>

                <hr class="border-slate-100 mb-8">

                <!-- JAM OPERASIONAL -->
                <h3 class="font-bold text-slate-800 mb-4">Jam Operasional Harian</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Jam Buka (WIB)</label>
                        <input type="time" name="open_time" value="{{ $settings['open_time'] ?? '06:00' }}"
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-50 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Jam Tutup (WIB)</label>
                        <input type="time" name="close_time" value="{{ $settings['close_time'] ?? '22:00' }}"
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-50 outline-none">
                    </div>
                </div>

                <button type="submit"
                    class="bg-blue-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-blue-700 transition-all">
                    💾 Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>
@endsection
