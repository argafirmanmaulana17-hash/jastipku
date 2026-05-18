@extends('layouts.app')
@section('title', 'Bantuan & FAQ - JastipKu')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <h1 class="font-display font-bold text-3xl text-slate-800 mb-4">Pusat Bantuan JastipKu</h1>
            <p class="text-slate-500">Temukan jawaban untuk pertanyaan yang sering ditanyakan di bawah ini.</p>
        </div>

        <div class="space-y-4">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 text-lg mb-2">Bagaimana cara pesan jastip?</h3>
                <p class="text-slate-600 text-sm">Cukup cari jastiper yang sedang aktif di halaman utama, klik tombol "Pilih Jastiper", lalu isi form barang apa saja yang ingin kamu titip beli.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 text-lg mb-2">Kapan saya harus membayar?</h3>
                <p class="text-slate-600 text-sm">Setelah jastiper mengonfirmasi ketersediaan barang dan total harga (termasuk ongkos jastip), kamu bisa mengirimkan bukti transfer melalui fitur Live Chat pesanan.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 text-lg mb-2">Apakah uang saya aman?</h3>
                <p class="text-slate-600 text-sm">Sangat aman! Seluruh transaksi dipantau oleh admin JastipKu. Jika barang tidak dibelikan, dana akan dikembalikan 100%.</p>
            </div>
        </div>

        <div class="mt-12 text-center bg-blue-50 p-8 rounded-3xl border border-blue-100">
            <h3 class="font-bold text-slate-800 mb-2">Masih bingung?</h3>
            <p class="text-sm text-slate-600 mb-4">Tim admin kami siap membantu kendalamu.</p>
            <a href="https://wa.me/6281234567890" target="_blank" class="inline-block bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                Hubungi Admin via WhatsApp
            </a>
        </div>

    </div>
</div>
@endsection