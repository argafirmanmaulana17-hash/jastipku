@extends('layouts.app')
@section('title', 'Masuk - JastipKu')

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
            <h1 class="font-display text-3xl font-800 text-slate-900">Selamat Datang!</h1>
            <p class="text-slate-500 mt-2 text-sm">Masuk ke akun JastipKu kamu</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                <div class="text-sm text-red-600">{{ $errors->first() }}</div>
            </div>
            @endif

            @if(session('status'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6">
                <div class="text-sm text-green-600">{{ session('status') }}</div>
            </div>
            @endif

            <form method="POST" action="/login" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kampus.ac.id" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordField" placeholder="••••••••" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm pr-12">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-slate-600">Ingat saya</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-4 rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Belum punya akun? <a href="/register" class="text-blue-600 font-semibold hover:underline">Daftar sekarang</a></p>
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
function togglePassword() {
    const field = document.getElementById('passwordField');
    field.type = field.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
