@extends('layouts.app')
@section('title', 'Chat Kami - JastipKu')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-10">
            <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">Hubungi Kami</span>
            <h1 class="font-display text-4xl font-800 text-slate-900 mt-2">Chat dengan Kami</h1>
            <p class="text-slate-500 mt-3">Ada pertanyaan? Kami siap membantu kamu!</p>
        </div>

        <!-- Online Status -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-slate-800 text-sm">Tim JastipKu</div>
                    <div class="flex items-center gap-1 text-xs text-green-600">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        Online · Balas dalam beberapa menit
                    </div>
                </div>
            </div>
            <div class="text-xs text-slate-500">Senin–Minggu · 07.00–22.00</div>
        </div>

        <!-- Chat Options -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <!-- WhatsApp -->
            <a href="https://wa.me/62812xxxxxxxx?text=Halo JastipKu, saya mau tanya..." target="_blank"
                class="card-hover bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-4 hover:border-green-300">
                <div class="w-14 h-14 bg-green-500 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                    💬
                </div>
                <div>
                    <div class="font-semibold text-slate-800">WhatsApp</div>
                    <div class="text-sm text-slate-500">Chat langsung via WA</div>
                    <div class="text-xs text-green-600 font-medium mt-1">Paling cepat dibalas ✓</div>
                </div>
            </a>

            <!-- Instagram -->
            <a href="https://instagram.com/jastipku" target="_blank"
                class="card-hover bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-4 hover:border-pink-300">
                <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-purple-600 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                    📸
                </div>
                <div>
                    <div class="font-semibold text-slate-800">Instagram</div>
                    <div class="text-sm text-slate-500">DM di Instagram</div>
                    <div class="text-xs text-pink-500 font-medium mt-1">@jastipku</div>
                </div>
            </a>
        </div>

        <!-- Live Chat Widget -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    💬
                </div>
                <div>
                    <div class="font-semibold text-white">Live Chat</div>
                    <div class="text-blue-200 text-xs">Powered by Tawk.to</div>
                </div>
            </div>

            <!-- Chat Area (akan diisi Tawk.to) -->
            <div id="liveChatArea" class="p-8 text-center">
                <div class="text-4xl mb-3">💬</div>
                <h3 class="font-semibold text-slate-700 mb-2">Live Chat Tersedia</h3>
                <p class="text-sm text-slate-500 mb-4">
                    Widget live chat aktif di pojok kanan bawah halaman ini.<br>
                    Klik tombol chat untuk mulai berbicara dengan tim kami!
                </p>
                <button onclick="Tawk_API.toggle()" class="bg-blue-600 text-white text-sm font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 transition-colors">
                    Buka Live Chat
                </button>
                <p class="text-xs text-slate-400 mt-3">
                    * Pastikan JavaScript aktif di browser kamu
                </p>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">
            <h2 class="font-display text-xl font-700 text-slate-800 mb-6">❓ Pertanyaan Umum</h2>
            <div class="space-y-4" id="faqContainer">
                @foreach([
                    ['q'=>'Berapa biaya jasa titip?','a'=>'Biaya jastip tergantung jarak dan kategori barang. Umumnya Rp 2.000 - Rp 10.000 per order. Biaya akan dikonfirmasi sebelum jastiper berangkat.'],
                    ['q'=>'Berapa lama proses pengiriman?','a'=>'Tergantung jarak dan kesibukan jastiper. Rata-rata 15-45 menit. Kamu bisa pantau status lewat halaman Tracking.'],
                    ['q'=>'Bagaimana sistem pembayarannya?','a'=>'Bayar DP 50% saat order dikonfirmasi, sisanya bayar tunai atau transfer saat barang diterima.'],
                    ['q'=>'Bagaimana jika barang tidak sesuai pesanan?','a'=>'Kami bertanggung jawab penuh. Hubungi kami via WhatsApp dan kami akan menyelesaikan masalah dalam 24 jam.'],
                    ['q'=>'Apakah bisa order di malam hari?','a'=>'Layanan kami aktif Senin-Minggu pukul 07.00-22.00. Di luar jam tersebut, order akan diproses keesokan harinya.'],
                ] as $i => $faq)
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button onclick="toggleFaq({{ $i }})" class="w-full flex items-center justify-between p-4 text-left hover:bg-slate-50 transition-colors">
                        <span class="font-medium text-slate-800 text-sm">{{ $faq['q'] }}</span>
                        <svg id="faqIcon{{ $i }}" class="w-4 h-4 text-slate-400 transition-transform flex-shrink-0 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="faqAnswer{{ $i }}" class="hidden px-4 pb-4 text-sm text-slate-500 leading-relaxed">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleFaq(index) {
    const answer = document.getElementById('faqAnswer' + index);
    const icon = document.getElementById('faqIcon' + index);
    answer.classList.toggle('hidden');
    icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
}
</script>
@endsection
