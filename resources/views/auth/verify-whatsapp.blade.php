@extends('layouts.app')

@section('title', 'Verifikasi WhatsApp - JastipKu')

@section('content')
    <div class="pt-24 pb-20 bg-slate-50 min-h-screen">
        <div class="max-w-md mx-auto px-4">
            <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-8">
                <div class="text-center mb-8">
                    <div
                        class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-3xl mx-auto mb-4">
                        💬
                    </div>

                    <h1 class="text-2xl font-extrabold text-slate-900">
                        Verifikasi WhatsApp
                    </h1>

                    <p class="text-sm text-slate-500 mt-2">
                        Kami perlu memastikan nomor WhatsApp kamu aktif sebelum membuat pesanan.
                    </p>
                </div>

                @if (session('success'))
                    <div
                        class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-5 text-sm font-semibold">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 text-sm">
                        <div class="font-bold mb-1">Ada yang perlu diperbaiki:</div>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-5">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">
                        Nomor WhatsApp
                    </div>
                    <div class="font-bold text-slate-900">
                        {{ Auth::user()->whatsapp ?? (Auth::user()->no_hp ?? 'Belum ada nomor') }}
                    </div>
                </div>

                <form method="POST" action="{{ route('whatsapp.send') }}" class="mb-5">
                    @csrf
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition">
                        Kirim OTP WhatsApp
                    </button>
                </form>

                @if (session('dev_otp'))
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl p-4 mb-5 text-sm">
                        <div class="font-bold">Mode Development</div>
                        <div>Kode OTP: <span class="text-lg font-extrabold">{{ session('dev_otp') }}</span></div>
                        <div class="text-xs mt-1">Nanti kalau sudah pakai API WhatsApp resmi, bagian ini dihapus.</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('whatsapp.check') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Masukkan OTP
                        </label>
                        <input type="text" name="otp" maxlength="6" inputmode="numeric" placeholder="123456"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-center text-xl font-bold tracking-widest focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition">
                        Verifikasi Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
