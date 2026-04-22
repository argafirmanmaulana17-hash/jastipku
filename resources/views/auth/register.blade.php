@extends('layouts.app')
@section('title', 'Daftar - JastipKu')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-slate-50 flex items-center justify-center px-4 py-20">
    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 mb-6">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </a>
            <h1 class="font-display text-3xl font-800 text-slate-900">Buat Akun Baru</h1>
            <p class="text-slate-500 mt-2 text-sm">Bergabung dengan komunitas jastip kampus!</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Role Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-3">Daftar sebagai:</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role_select" value="user" class="sr-only peer" checked>
                        <div class="peer-checked:border-blue-500 peer-checked:bg-blue-50 border-2 border-slate-200 rounded-xl p-4 text-center transition-all">
                            <div class="text-2xl mb-1">🛒</div>
                            <div class="text-sm font-semibold text-slate-700">Pembeli</div>
                            <div class="text-xs text-slate-400">Mau titip barang</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role_select" value="jastiper" class="sr-only peer">
                        <div class="peer-checked:border-blue-500 peer-checked:bg-blue-50 border-2 border-slate-200 rounded-xl p-4 text-center transition-all">
                            <div class="text-2xl mb-1">🛵</div>
                            <div class="text-sm font-semibold text-slate-700">Jastiper</div>
                            <div class="text-xs text-slate-400">Mau jadi jastiper</div>
                        </div>
                    </label>
                </div>
            </div>

            <form method="POST" action="/register" class="space-y-4" id="registerForm">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="user">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap kamu" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kampus.ac.id" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">No. WhatsApp *</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08xx-xxxx-xxxx" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                </div>

                <!-- Jastiper extra fields -->
                <div id="jastiperFields" class="hidden space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Area Layanan *</label>
                        <input type="text" name="area_layanan" value="{{ old('area_layanan') }}" placeholder="Contoh: Kantin, Koperasi, Mall xxx"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Kendaraan</label>
                        <select name="kendaraan" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm bg-white">
                            <option value="motor">🛵 Motor</option>
                            <option value="sepeda">🚲 Sepeda</option>
                            <option value="jalan">🚶 Jalan Kaki</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password *</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                </div>

                <div class="flex items-start gap-3 pt-2">
                    <input type="checkbox" name="agree" id="agree" required class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <label for="agree" class="text-xs text-slate-500 cursor-pointer">
                        Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat & ketentuan</a> dan <a href="#" class="text-blue-600 hover:underline">kebijakan privasi</a> JastipKu.
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-4 rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 mt-2">
                    Buat Akun
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Sudah punya akun? <a href="/login" class="text-blue-600 font-semibold hover:underline">Masuk di sini</a></p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="/" class="text-sm text-slate-500 hover:text-slate-700">← Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Role selection
    document.querySelectorAll('input[name="role_select"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('roleInput').value = this.value;
            const jastiperFields = document.getElementById('jastiperFields');
            if (this.value === 'jastiper') {
                jastiperFields.classList.remove('hidden');
            } else {
                jastiperFields.classList.add('hidden');
            }
        });
    });
</script>
@endsection
