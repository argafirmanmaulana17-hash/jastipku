@extends('layouts.app')
@section('title', 'Pesan Jastip - JastipKu')

@section('content')
    <div class="pt-24 pb-20 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-10">
                <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">Form Order</span>
                <h1 class="font-display text-4xl font-800 text-slate-900 mt-2">Pesan Jastip</h1>
                <p class="text-slate-500 mt-3">
                    Isi form di bawah dengan lengkap ya, biar jastiper bisa proses ordermu dengan cepat!
                </p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">✅</div>
                    <div>
                        <div class="font-semibold text-green-800">Order Berhasil Dikirim!</div>
                        <div class="text-sm text-green-600">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                    <div class="font-semibold text-red-800 mb-2">Ada yang perlu diperbaiki:</div>
                    <ul class="text-sm text-red-600 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
                <form action="/order" method="POST">
                    @csrf

                    <div class="mb-8">
                        <h2 class="font-display text-lg font-700 text-slate-800 mb-4 flex items-center gap-2">
                            <span
                                class="w-7 h-7 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm">1</span>
                            Informasi Pemesan
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap *</label>
                                <input type="text" name="nama" value="{{ old('nama', Auth::user()->name) }}"
                                    placeholder="Nama kamu" required readonly
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 cursor-not-allowed text-slate-500 outline-none transition-all text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">No. WhatsApp *</label>
                                <input type="text" name="whatsapp"
                                    value="{{ Auth::user()->whatsapp ?? (Auth::user()->no_hp ?? '') }}"
                                    placeholder="08xx-xxxx-xxxx" readonly
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 cursor-not-allowed text-slate-500 outline-none transition-all text-sm">

                                <p class="text-xs text-slate-400 mt-1">
                                    Nomor WhatsApp diambil dari akun kamu.
                                </p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Lokasi Pengiriman *</label>

                                @if ($savedAddresses->isNotEmpty())
                                    <select id="select_alamat" onchange="pilihAlamatOtomatis(this.value)"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm bg-white mb-3">
                                        <option value="">-- Pilih Alamat Pengiriman --</option>
                                        @foreach ($savedAddresses as $addr)
                                            <option value="{{ $addr->alamat_lengkap }}">
                                                {{ strtoupper($addr->label) }} - {{ $addr->alamat_lengkap }}
                                            </option>
                                        @endforeach
                                        <option value="tambah_baru" class="text-blue-600 font-bold">
                                            ➕ Gunakan Alamat Baru...
                                        </option>
                                    </select>
                                @endif

                                <div id="box_alamat_baru"
                                    class="{{ $savedAddresses->isEmpty() ? '' : 'hidden' }} bg-amber-50 border border-amber-200 rounded-xl p-4 mb-3">
                                    <p class="text-xs font-semibold text-amber-700 mb-2 flex items-center gap-1">
                                        💡 <span class="uppercase tracking-wider">Tulis Alamat Baru</span>
                                    </p>

                                    <input type="text" name="label_alamat_baru" id="label_alamat_baru"
                                        placeholder="Simpan alamat ini sebagai? Contoh: Kosan, Rumah, Kampus"
                                        class="w-full px-4 py-2 mb-2 rounded-lg border border-amber-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all text-sm bg-white">

                                    <textarea id="lokasi_antar_baru" rows="2"
                                        placeholder="Detail pengiriman: Gedung A lantai 2, atau Kos Blok B no.12"
                                        class="w-full px-4 py-2 rounded-lg border border-amber-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all text-sm resize-none"></textarea>
                                </div>

                                <input type="hidden" name="lokasi_antar" id="lokasi_antar_real" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="font-display text-lg font-700 text-slate-800 mb-4 flex items-center gap-2">
                            <span
                                class="w-7 h-7 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm">2</span>
                            Detail Pesanan
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Kategori *</label>
                                <select name="kategori" id="kategori" required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm bg-white">
                                    <option value="">Pilih kategori...</option>
                                    <option value="makanan" {{ old('kategori') == 'makanan' ? 'selected' : '' }}>🍔 Makanan
                                        & Minuman</option>
                                    <option value="atk" {{ old('kategori') == 'atk' ? 'selected' : '' }}>📚 Alat Tulis &
                                        Buku</option>
                                    <option value="obat" {{ old('kategori') == 'obat' ? 'selected' : '' }}>💊 Obat &
                                        Kesehatan</option>
                                    <option value="fashion" {{ old('kategori') == 'fashion' ? 'selected' : '' }}>👕 Fashion
                                    </option>
                                    <option value="elektronik" {{ old('kategori') == 'elektronik' ? 'selected' : '' }}>💻
                                        Elektronik</option>
                                    <option value="minimarket" {{ old('kategori') == 'minimarket' ? 'selected' : '' }}>🛒
                                        Minimarket</option>
                                    <option value="kado" {{ old('kategori') == 'kado' ? 'selected' : '' }}>🎁 Kado
                                    </option>
                                    <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>✨ Lainnya
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Lokasi Pengambilan *</label>
                                <input type="text" name="lokasi_ambil" value="{{ old('lokasi_ambil') }}"
                                    placeholder="Contoh: Kantin Utama, Indomaret Jl. xxx" required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                            </div>

                            <div id="menu-pricelist-box"
                                class="sm:col-span-2 {{ old('kategori') == 'makanan' ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Pilih Menu Price List *
                                </label>

                                <div class="space-y-3">
                                    @foreach ($menuItems as $menu)
                                        <div
                                            class="flex items-center justify-between gap-3 border border-slate-200 rounded-xl p-3 bg-white">
                                            <div>
                                                <div class="font-semibold text-slate-800 text-sm">
                                                    {{ $menu->nama }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $menu->toko ?: 'Tanpa toko' }} • Rp
                                                    {{ number_format($menu->harga, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <button type="button" onclick="ubahQty({{ $menu->id }}, -1)"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 font-bold">
                                                    -
                                                </button>

                                                <input type="hidden" name="menu_items[{{ $loop->index }}][id]"
                                                    value="{{ $menu->id }}">

                                                <input type="number" id="qty-{{ $menu->id }}"
                                                    name="menu_items[{{ $loop->index }}][qty]"
                                                    value="{{ old('menu_items.' . $loop->index . '.qty', 0) }}"
                                                    min="0" data-harga="{{ $menu->harga }}"
                                                    data-toko="{{ $menu->toko }}"
                                                    class="qty-menu w-16 text-center px-2 py-2 rounded-lg border border-slate-200 text-sm"
                                                    oninput="hitungTotalMenu()">

                                                <button type="button" onclick="ubahQty({{ $menu->id }}, 1)"
                                                    class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-slate-600">Total Menu</span>
                                        <span class="font-bold text-slate-900" id="total-menu-label">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-slate-600">Ongkos Jastip</span>
                                        <span class="font-bold text-slate-900" id="ongkos-label">Rp 0</span>
                                    </div>
                                    <div class="border-t border-blue-200 mt-2 pt-2 flex justify-between">
                                        <span class="font-bold text-blue-700">Total Bayar</span>
                                        <span class="font-extrabold text-blue-700" id="total-bayar-label">Rp 0</span>
                                    </div>
                                </div>

                                <p class="text-xs text-slate-500 mt-2">
                                    Ongkos jastip menyesuaikan jumlah menu: <b>1-3 item</b> (Rp 3.000), <b>4-8 item</b> (Rp
                                    5.000), dan <b>>8 item</b> (Rp 10.000).
                                </p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Detail Pesanan
                                    (Opsional)</label>
                                <textarea name="detail_pesanan" rows="4"
                                    placeholder="Opsional: tulis detail tambahan seperti ukuran, warna, jumlah, atau instruksi khusus."
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm resize-none">{{ old('detail_pesanan') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Estimasi Budget</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-medium">Rp</span>
                                    <input type="number" name="budget" id="budget" value="{{ old('budget') }}"
                                        placeholder="Boleh kosong untuk barang penawaran"
                                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Waktu Dibutuhkan</label>
                                <select name="waktu"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm bg-white">
                                    <option value="segera">⚡ Secepatnya</option>
                                    <option value="1jam">🕐 Dalam 1 Jam</option>
                                    <option value="2jam">🕑 Dalam 2 Jam</option>
                                    <option value="hari_ini">📅 Hari Ini</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="font-display text-lg font-700 text-slate-800 mb-4 flex items-center gap-2">
                            <span
                                class="w-7 h-7 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm">3</span>
                            Metode Pembayaran
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach ([['value' => 'dana', 'label' => 'DANA', 'icon' => '💙'], ['value' => 'cash', 'label' => 'Cash (COD)', 'icon' => '💵'], ['value' => 'transfer', 'label' => 'Transfer Bank', 'icon' => '🏦']] as $pay)
                                <label class="cursor-pointer">
                                    <input type="radio" name="pembayaran" value="{{ $pay['value'] }}"
                                        class="sr-only peer" {{ $loop->first ? 'checked' : '' }}>
                                    <div
                                        class="peer-checked:border-blue-500 peer-checked:bg-blue-50 border-2 border-slate-200 rounded-xl p-4 text-center transition-all hover:border-blue-300">
                                        <div class="text-2xl mb-1">{{ $pay['icon'] }}</div>
                                        <div class="text-sm font-semibold text-slate-700">{{ $pay['label'] }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Tambahan (opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Ada instruksi khusus? Tulis di sini..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm resize-none">{{ old('catatan', optional(Auth::user()->orders()->latest()->first())->catatan) }}</textarea>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-8">
                        <div class="flex gap-3">
                            <div class="text-blue-500 mt-0.5">ℹ️</div>
                            <div class="text-sm text-blue-700">
                                <strong>Cara Pembayaran:</strong> Bayar DP 50% saat order dikonfirmasi, sisanya saat barang
                                diterima.
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-semibold py-4 rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Kirim Order Sekarang
                    </button>
                </form>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">
                    Butuh bantuan?
                    <a href="/chat" class="text-blue-600 font-semibold hover:underline">Chat dengan kami</a>
                    atau hubungi WhatsApp
                    <a href="https://wa.me/62812xxxxxxxx"
                        class="text-blue-600 font-semibold hover:underline">0812-xxxx-xxxx</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const selectAlamat = document.getElementById('select_alamat');
        const boxAlamatBaru = document.getElementById('box_alamat_baru');
        const lokasiAntarReal = document.getElementById('lokasi_antar_real');
        const lokasiAntarBaru = document.getElementById('lokasi_antar_baru');
        const labelAlamatBaru = document.getElementById('label_alamat_baru');

        function pilihAlamatOtomatis(val) {
            if (val === 'tambah_baru') {
                boxAlamatBaru.classList.remove('hidden');
                lokasiAntarReal.value = lokasiAntarBaru.value;
                labelAlamatBaru.disabled = false;
                lokasiAntarBaru.required = true;
            } else {
                boxAlamatBaru.classList.add('hidden');
                lokasiAntarReal.value = val;
                labelAlamatBaru.disabled = true;
                labelAlamatBaru.value = '';
                lokasiAntarBaru.required = false;
            }
        }

        if (lokasiAntarBaru) {
            lokasiAntarBaru.addEventListener('input', function() {
                if (!selectAlamat || selectAlamat.value === 'tambah_baru') {
                    lokasiAntarReal.value = this.value;
                }
            });
        }

        const kategoriSelect = document.getElementById('kategori');
        const menuBox = document.getElementById('menu-pricelist-box');
        const budgetInput = document.getElementById('budget');
        const lokasiAmbilInput = document.querySelector('input[name="lokasi_ambil"]');

        function formatRupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        function ubahQty(menuId, change) {
            const input = document.getElementById('qty-' + menuId);
            if (!input) return;

            let value = parseInt(input.value || '0');
            value += change;

            if (value < 0) value = 0;

            input.value = value;
            hitungTotalMenu();
        }

        function hitungTotalMenu() {
            let total = 0;
            let totalQty = 0; // Variabel baru untuk menghitung total jumlah barang
            let tokoPertama = '';

            document.querySelectorAll('.qty-menu').forEach(function(input) {
                const qty = parseInt(input.value || '0');
                const harga = parseInt(input.dataset.harga || '0');
                const toko = input.dataset.toko || '';

                if (qty > 0) {
                    total += qty * harga;
                    totalQty += qty; // Menambahkan qty ke total keseluruhan

                    if (!tokoPertama && toko) {
                        tokoPertama = toko;
                    }
                }
            });

            // LOGIKA TARIF ONGKOS BARU (Sesuai dengan OrderController kamu)
            let ongkos = 0;
            if (totalQty > 0 && totalQty <= 3) {
                ongkos = 3000;
            } else if (totalQty > 3 && totalQty <= 8) {
                ongkos = 5000;
            } else if (totalQty > 8) {
                ongkos = 10000;
            }

            const totalBayar = total + ongkos;

            document.getElementById('total-menu-label').innerText = formatRupiah(total);
            document.getElementById('ongkos-label').innerText = formatRupiah(ongkos);
            document.getElementById('total-bayar-label').innerText = formatRupiah(totalBayar);

            if (budgetInput) {
                budgetInput.value = total > 0 ? total : '';
            }

            if (lokasiAmbilInput && tokoPertama) {
                lokasiAmbilInput.value = tokoPertama;
            }
        }

        function toggleHargaFlow() {
            if (!kategoriSelect) return;

            const isMakanan = kategoriSelect.value === 'makanan';

            if (isMakanan) {
                menuBox.classList.remove('hidden');
                budgetInput.readOnly = true;
                budgetInput.placeholder = 'Otomatis dari keranjang menu';
            } else {
                menuBox.classList.add('hidden');
                budgetInput.readOnly = false;
                budgetInput.placeholder = 'Estimasi saja, boleh kosong';

                document.querySelectorAll('.qty-menu').forEach(function(input) {
                    input.value = 0;
                });

                hitungTotalMenu();
            }
        }

        kategoriSelect?.addEventListener('change', toggleHargaFlow);

        document.addEventListener('DOMContentLoaded', function() {
            if (!selectAlamat) {
                lokasiAntarReal.value = lokasiAntarBaru.value;
                lokasiAntarBaru.required = true;
            } else {
                pilihAlamatOtomatis(selectAlamat.value);
            }

            toggleHargaFlow();
            hitungTotalMenu();
        });
    </script>
@endsection
