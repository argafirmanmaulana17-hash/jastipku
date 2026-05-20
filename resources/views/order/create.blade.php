@extends('layouts.app')
@section('title', 'Pesan Jastip - JastipKu')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-10">
            <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">Form Order</span>
            <h1 class="font-display text-4xl font-800 text-slate-900 mt-2">Pesan Jastip</h1>
            <p class="text-slate-500 mt-3">Isi form di bawah dengan lengkap ya, biar jastiper bisa proses ordermu dengan cepat!</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">✅</div>
            <div>
                <div class="font-semibold text-green-800">Order Berhasil Dikirim!</div>
                <div class="text-sm text-green-600">{{ session('success') }}</div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
            <div class="font-semibold text-red-800 mb-2">Ada yang perlu diperbaiki:</div>
            <ul class="text-sm text-red-600 list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            <form action="/order" method="POST">
                @csrf

                <!-- Informasi Pemesan -->
                <div class="mb-8">
                    <h2 class="font-display text-lg font-700 text-slate-800 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm">1</span>
                        Informasi Pemesan
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                       <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="nama" value="{{ old('nama', Auth::user()->name) }}" placeholder="Nama kamu" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm bg-slate-50 cursor-not-allowed text-slate-500" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">No. WhatsApp *</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', Auth::user()->whatsapp ?? Auth::user()->no_hp ?? '') }}" placeholder="08xx-xxxx-xxxx" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                        </div>
                       <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Lokasi Pengiriman *</label>
                            
                            @if($savedAddresses->isNotEmpty())
                                <select id="select_alamat" onchange="pilihAlamatOtomatis(this.value)"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm bg-white mb-3">
                                    <option value="">-- Pilih Alamat Pengiriman --</option>
                                    @foreach($savedAddresses as $addr)
                                        <option value="{{ $addr->alamat_lengkap }}">
                                            {{ strtoupper($addr->label) }} - {{ $addr->alamat_lengkap }}
                                        </option>
                                    @endforeach
                                    <option value="tambah_baru" class="text-blue-600 font-bold">➕ Gunakan Alamat Baru...</option>
                                </select>
                            @endif

                            <div id="box_alamat_baru" class="{{ $savedAddresses->isEmpty() ? '' : 'hidden' }} bg-amber-50 border border-amber-200 rounded-xl p-4 mb-3">
                                <p class="text-xs font-semibold text-amber-700 mb-2 flex items-center gap-1">
                                    💡 <span class="uppercase tracking-wider">Tulis Alamat Baru</span>
                                </p>
                                <input type="text" name="label_alamat_baru" id="label_alamat_baru" placeholder="Simpan alamat ini sebagai? (Contoh: Kosan Blok A, Rumah)" 
                                    class="w-full px-4 py-2 mb-2 rounded-lg border border-amber-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all text-sm bg-white">
                                
                                <textarea id="lokasi_antar_baru" rows="2" placeholder="Detail pengiriman: Gedung A lantai 2, atau Kos Blok B no.12"
                                    class="w-full px-4 py-2 rounded-lg border border-amber-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all text-sm resize-none"></textarea>
                            </div>

                            <input type="hidden" name="lokasi_antar" id="lokasi_antar_real" required>

                            <script>
                                const selectAlamat = document.getElementById('select_alamat');
                                const boxAlamatBaru = document.getElementById('box_alamat_baru');
                                const lokasiAntarReal = document.getElementById('lokasi_antar_real');
                                const lokasiAntarBaru = document.getElementById('lokasi_antar_baru');
                                const labelAlamatBaru = document.getElementById('label_alamat_baru');

                                function pilihAlamatOtomatis(val) {
                                    if (val === 'tambah_baru') {
                                        // Buka kotak ketik manual
                                        boxAlamatBaru.classList.remove('hidden');
                                        lokasiAntarReal.value = lokasiAntarBaru.value;
                                        labelAlamatBaru.disabled = false;
                                        lokasiAntarBaru.required = true;
                                    } else {
                                        // Sembunyikan kotak ketik manual, pakai nilai dari dropdown
                                        boxAlamatBaru.classList.add('hidden');
                                        lokasiAntarReal.value = val;
                                        labelAlamatBaru.disabled = true;
                                        labelAlamatBaru.value = ''; 
                                        lokasiAntarBaru.required = false;
                                    }
                                }

                                // Sinkronisasi ketikan teks di textarea baru ke dalam input hidden Laravel
                                if (lokasiAntarBaru) {
                                    lokasiAntarBaru.addEventListener('input', function() {
                                        if (!selectAlamat || selectAlamat.value === 'tambah_baru') {
                                            lokasiAntarReal.value = this.value;
                                        }
                                    });
                                }

                                // Jalankan pengecekan awal saat halaman pertama kali dibuka
                                document.addEventListener("DOMContentLoaded", function() {
                                    if (!selectAlamat) {
                                        // Kasus user baru yang belum punya alamat sama sekali
                                        lokasiAntarReal.value = lokasiAntarBaru.value;
                                        lokasiAntarBaru.required = true;
                                    } else {
                                        // Kasus user lama yang sudah punya pilihan alamat
                                        pilihAlamatOtomatis(selectAlamat.value);
                                    }
                                });
                            </script>
                        </div>

                <!-- Detail Order -->
                <div class="mb-8">
                    <h2 class="font-display text-lg font-700 text-slate-800 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm">2</span>
                        Detail Pesanan
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Kategori *</label>
                            <select name="kategori" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm bg-white">
                                <option value="">Pilih kategori...</option>
                                <option value="makanan" {{ old('kategori') == 'makanan' ? 'selected' : '' }}>🍔 Makanan & Minuman</option>
                                <option value="atk" {{ old('kategori') == 'atk' ? 'selected' : '' }}>📚 Alat Tulis & Buku</option>
                                <option value="obat" {{ old('kategori') == 'obat' ? 'selected' : '' }}>💊 Obat & Kesehatan</option>
                                <option value="fashion" {{ old('kategori') == 'fashion' ? 'selected' : '' }}>👕 Fashion</option>
                                <option value="elektronik" {{ old('kategori') == 'elektronik' ? 'selected' : '' }}>💻 Elektronik</option>
                                <option value="minimarket" {{ old('kategori') == 'minimarket' ? 'selected' : '' }}>🛒 Minimarket</option>
                                <option value="kado" {{ old('kategori') == 'kado' ? 'selected' : '' }}>🎁 Kado</option>
                                <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>✨ Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Lokasi Pengambilan *</label>
                            <input type="text" name="lokasi_ambil" value="{{ old('lokasi_ambil') }}" placeholder="Contoh: Kantin Utama, Indomaret Jl. xxx" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Detail Pesanan *</label>
                            <textarea name="detail_pesanan" rows="4" placeholder="Jelaskan detailnya: nama produk, ukuran, warna, jumlah, dll. Semakin detail semakin baik!" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm resize-none">{{ old('detail_pesanan') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Estimasi Budget *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-medium">Rp</span>
                                <input type="number" name="budget" value="{{ old('budget') }}" placeholder="50000" required
                                    class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Waktu Dibutuhkan</label>
                            <select name="waktu" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm bg-white">
                                <option value="segera">⚡ Secepatnya</option>
                                <option value="1jam">🕐 Dalam 1 Jam</option>
                                <option value="2jam">🕑 Dalam 2 Jam</option>
                                <option value="hari_ini">📅 Hari Ini</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran -->
                <div class="mb-8">
                    <h2 class="font-display text-lg font-700 text-slate-800 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm">3</span>
                        Metode Pembayaran
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach([['value'=>'dana','label'=>'DANA','icon'=>'💙'],['value'=>'cash','label'=>'Cash (COD)','icon'=>'💵'],['value'=>'transfer','label'=>'Transfer Bank','icon'=>'🏦']] as $pay)
                        <label class="cursor-pointer">
                            <input type="radio" name="pembayaran" value="{{ $pay['value'] }}" class="sr-only peer" {{ $loop->first ? 'checked' : '' }}>
                            <div class="peer-checked:border-blue-500 peer-checked:bg-blue-50 border-2 border-slate-200 rounded-xl p-4 text-center transition-all hover:border-blue-300">
                                <div class="text-2xl mb-1">{{ $pay['icon'] }}</div>
                                <div class="text-sm font-semibold text-slate-700">{{ $pay['label'] }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Catatan -->
               <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Tambahan (opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Ada instruksi khusus? Tulis di sini..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm resize-none">{{ old('catatan', optional(Auth::user()->orders()->latest()->first())->catatan) }}</textarea>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-8">
                    <div class="flex gap-3">
                        <div class="text-blue-500 mt-0.5">ℹ️</div>
                        <div class="text-sm text-blue-700">
                            <strong>Cara Pembayaran:</strong> Bayar DP 50% saat order dikonfirmasi, sisanya saat barang diterima. Transfer ke DANA Bisnis JastipKu atau bayar tunai ke jastiper.
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-4 rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Order Sekarang
                </button>
            </form>
        </div>

        <!-- Help Box -->
        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500">Butuh bantuan? <a href="/chat" class="text-blue-600 font-semibold hover:underline">Chat dengan kami</a> atau hubungi WhatsApp <a href="https://wa.me/62812xxxxxxxx" class="text-blue-600 font-semibold hover:underline">0812-xxxx-xxxx</a></p>
        </div>
    </div>
</div>
@endsection
