@extends('layouts.app')

@section('title', 'Kelola Pembeli - JastipKu')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('dashboard.admin.index') }}"
                   class="inline-flex items-center gap-2 text-sm text-blue-600 font-semibold hover:underline mb-3">
                    ← Kembali ke Dashboard Admin
                </a>

                <h1 class="font-display text-3xl font-800 text-slate-900">
                    Kelola Pembeli
                </h1>

                <p class="text-slate-500 mt-2">
                    Daftar akun pembeli/customer yang terdaftar di platform JastipKu.
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm">
                <div class="text-xs text-slate-400 uppercase font-bold tracking-wider">
                    Total Pembeli
                </div>
                <div class="text-2xl font-extrabold text-slate-900">
                    {{ $users->total() }}
                </div>
            </div>
        </div>

        <!-- Alert -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 text-sm font-semibold">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Info Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-2xl mb-2">👥</div>
                <div class="text-sm text-slate-500">Akun Pembeli</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $users->total() }}</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-2xl mb-2">🛒</div>
                <div class="text-sm text-slate-500">Role</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">User</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-2xl mb-2">📋</div>
                <div class="text-sm text-slate-500">Data Ditampilkan</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $users->count() }}</div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-display text-xl font-700 text-slate-900">
                        Data Pembeli
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Menampilkan nama, email, WhatsApp, dan tanggal daftar pembeli.
                    </p>
                </div>
            </div>

            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr class="text-left text-slate-500">
                                <th class="px-6 py-4 font-semibold">No</th>
                                <th class="px-6 py-4 font-semibold">Pembeli</th>
                                <th class="px-6 py-4 font-semibold">Email</th>
                                <th class="px-6 py-4 font-semibold">WhatsApp</th>
                                <th class="px-6 py-4 font-semibold">Tanggal Daftar</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $index => $user)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $users->firstItem() + $index }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                            </div>

                                            <div>
                                                <div class="font-semibold text-slate-900">
                                                    {{ $user->name ?? '-' }}
                                                </div>
                                                <div class="text-xs text-slate-400">
                                                    ID: {{ $user->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $user->email ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @php
                                            $phone = $user->whatsapp ?? $user->no_hp ?? null;
                                        @endphp

                                        @if($phone)
                                            <a href="https://wa.me/62{{ ltrim($phone, '0') }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-1 text-green-600 font-semibold hover:underline">
                                                💬 {{ $phone }}
                                            </a>
                                        @else
                                            <span class="text-slate-400">Belum ada</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                            Pembeli
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-5xl mb-4">👤</div>
                    <div class="font-semibold text-slate-700">
                        Belum ada pembeli terdaftar
                    </div>
                    <div class="text-sm text-slate-400 mt-1">
                        Data pembeli akan muncul setelah user melakukan registrasi.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection