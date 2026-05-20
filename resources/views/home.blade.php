@extends('layouts.app')
@section('title', 'JastipKu - Jasa Titip Kampus Terpercaya')

@section('styles')
<style>
    .hero-bg {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 50%, #eff6ff 100%);
        position: relative;
        overflow: hidden;
    }
    .hero-bg::before {
        content: '';
        position: absolute;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(37,99,235,0.08) 0%, transparent 70%);
        top: -100px; right: -100px;
        border-radius: 50%;
    }
    .hero-bg::after {
        content: '';
        position: absolute;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(96,165,250,0.1) 0%, transparent 70%);
        bottom: -50px; left: -50px;
        border-radius: 50%;
    }
    .step-line::after {
        content: '';
        position: absolute;
        top: 24px; left: calc(50% + 24px);
        width: calc(100% - 48px); height: 2px;
        background: linear-gradient(90deg, #2563eb, #93c5fd);
    }
    .testimonial-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
    }
    .testimonial-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 8px 30px rgba(37,99,235,0.1);
    }
</style>
@endsection

@section('content')

<!-- HERO SECTION -->
<section class="hero-bg min-h-screen flex items-center pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="animate-fadeInUp">
                <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 rounded-full px-4 py-2 mb-6">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-blue-700">🎓 Khusus Mahasiswa Kampus</span>
                </div>

                <h1 class="font-display text-5xl lg:text-6xl font-800 text-slate-900 leading-tight mb-6">
                    Titip Apa Aja,<br>
                    <span class="gradient-text">Kami Siap</span><br>
                    Ambilkan!
                </h1>

                <p class="text-lg text-slate-500 leading-relaxed mb-8 max-w-lg">
                    Layanan jasa titip terpercaya di lingkungan kampus. Mau jajan, beli alat tulis, atau apapun — tinggal order, kami yang urus!
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="order" class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white font-semibold px-8 py-4 rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Pesan Sekarang
                    </a>
                    <a href="/tracking" class="inline-flex items-center justify-center gap-2 bg-white text-slate-700 font-semibold px-8 py-4 rounded-2xl hover:bg-slate-50 transition-all border border-slate-200 hover:border-blue-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Cek Tracking
                    </a>
                </div>

                <!-- Stats -->
                <div class="flex gap-8 mt-12 pt-8 border-t border-blue-100">
                    <div>
                        <div class="font-display text-3xl font-800 text-blue-600">500+</div>
                        <div class="text-sm text-slate-500 mt-1">Order Selesai</div>
                    </div>
                    <div>
                        <div class="font-display text-3xl font-800 text-blue-600">20+</div>
                        <div class="text-sm text-slate-500 mt-1">Jastiper Aktif</div>
                    </div>
                    <div>
                        <div class="font-display text-3xl font-800 text-blue-600">4.9★</div>
                        <div class="text-sm text-slate-500 mt-1">Rating Rata-rata</div>
                    </div>
                </div>
            </div>

            <!-- Right Illustration -->
            <div class="hidden lg:flex justify-center">
                <div class="relative animate-float">
                    <div class="w-80 h-80 bg-gradient-to-br from-blue-500 to-blue-700 rounded-3xl shadow-2xl shadow-blue-300 flex items-center justify-center">
                        <svg class="w-40 h-40 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <!-- Floating cards -->
                    <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">✅</div>
                        <div>
                            <div class="text-xs text-slate-500">Order #1234</div>
                            <div class="text-sm font-semibold text-slate-800">Selesai!</div>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">🛵</div>
                        <div>
                            <div class="text-xs text-slate-500">Jastiper OTW</div>
                            <div class="text-sm font-semibold text-slate-800">5 menit lagi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">Cara Kerja</span>
            <h2 class="font-display text-4xl font-800 text-slate-900 mt-2">Semudah 3 Langkah</h2>
            <p class="text-slate-500 mt-3 max-w-md mx-auto">Tidak perlu ribet, cukup ikuti langkah berikut dan barangmu akan segera sampai!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['step'=>'01','icon'=>'📝','title'=>'Isi Form Order','desc'=>'Ceritakan apa yang mau kamu titip, dari mana, dan berapa budget kamu.'],
                ['step'=>'02','icon'=>'🛵','title'=>'Jastiper Berangkat','desc'=>'Jastiper kami yang ada di dekat lokasi akan menerima dan segera berangkat.'],
                ['step'=>'03','icon'=>'🎉','title'=>'Barang Sampai!','desc'=>'Barang diantarkan langsung ke kamu. Bayar setelah barang di tangan!'],
            ] as $item)
            <div class="text-center card-hover bg-slate-50 rounded-2xl p-8">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-lg shadow-blue-200">
                    {{ $item['icon'] }}
                </div>
                <div class="text-xs font-bold text-blue-400 mb-2">STEP {{ $item['step'] }}</div>
                <h3 class="font-display text-xl font-700 text-slate-800 mb-3">{{ $item['title'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">Layanan Kami</span>
            <h2 class="font-display text-4xl font-800 text-slate-900 mt-2">Apa Saja Bisa Dijastip!</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['icon'=>'🍔','title'=>'Makanan & Minuman','color'=>'bg-orange-50 border-orange-200'],
                ['icon'=>'📚','title'=>'Alat Tulis & Buku','color'=>'bg-blue-50 border-blue-200'],
                ['icon'=>'💊','title'=>'Obat & Kesehatan','color'=>'bg-green-50 border-green-200'],
                ['icon'=>'👕','title'=>'Fashion & Outfit','color'=>'bg-purple-50 border-purple-200'],
                ['icon'=>'💻','title'=>'Elektronik & Aksesoris','color'=>'bg-slate-50 border-slate-200'],
                ['icon'=>'🛒','title'=>'Belanja Minimarket','color'=>'bg-yellow-50 border-yellow-200'],
                ['icon'=>'🎁','title'=>'Kado & Hadiah','color'=>'bg-pink-50 border-pink-200'],
                ['icon'=>'✨','title'=>'Dan Lainnya...','color'=>'bg-indigo-50 border-indigo-200'],
            ] as $service)
            <div class="card-hover {{ $service['color'] }} border rounded-2xl p-6 text-center">
                <div class="text-3xl mb-3">{{ $service['icon'] }}</div>
                <div class="text-sm font-semibold text-slate-700">{{ $service['title'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ACTIVE JASTIPERS -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">Jastiper</span>
                <h2 class="font-display text-4xl font-800 text-slate-900 mt-2">Jastiper Aktif Sekarang</h2>
            </div>
            <a href="/order" class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                Pesan Sekarang →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($activeJastipers as $jastiper)
            <div class="card-hover bg-white border border-slate-200 rounded-2xl p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($jastiper->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800">{{ $jastiper->name }}</div>
                            <div class="text-xs text-slate-500">{{ $jastiper->area_layanan ?? 'Semua Area' }}</div>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        {{ ucfirst($jastiper->status) }}
                    </span>
                </div>
                <div class="flex gap-4 text-sm">
                    <div>
                        <span class="text-slate-500">Order</span>
                        <div class="font-semibold text-slate-800">{{ $jastiper->total_order }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500">Rating</span>
                        <div class="font-semibold text-yellow-500">★ {{ number_format($jastiper->rating, 1) }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 bg-slate-50 rounded-2xl p-8 text-center text-slate-500 border border-slate-200">
                Belum ada jastiper yang sedang aktif saat ini. Yuk, daftar jadi jastiper!
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">Testimoni</span>
            <h2 class="font-display text-4xl font-800 text-slate-900 mt-2">Kata Mereka</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['name'=>'Dina F.','prodi'=>'Teknik Informatika','text'=>'Keren banget! Lagi ngerjain tugas tapi laper, tinggal order lewat JastipKu, 15 menit langsung dateng. Recommended!','rating'=>5],
                ['name'=>'Reza M.','prodi'=>'Manajemen','text'=>'Udah langganan dari semester 1. Jastipernya ramah-ramah dan barang selalu sesuai pesanan. Harga juga transparan!','rating'=>5],
                ['name'=>'Ayu P.','prodi'=>'Akuntansi','text'=>'Sistem trackingnya mantep, bisa tau posisi jastiper kita lagi di mana. Nggak perlu nanya-nanya terus!','rating'=>5],
            ] as $testi)
            <div class="testimonial-card">
                <div class="flex text-yellow-400 text-lg mb-4">
                    @for($i=0;$i<$testi['rating'];$i++) ★ @endfor
                </div>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">"{{ $testi['text'] }}"</p>
                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                        {{ substr($testi['name'], 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-800">{{ $testi['name'] }}</div>
                        <div class="text-xs text-slate-500">{{ $testi['prodi'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="py-20 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-4xl lg:text-5xl font-800 text-white mb-6">
            Siap Titip Sekarang?
        </h2>
        <p class="text-blue-200 text-lg mb-10 max-w-md mx-auto">
            Ribuan mahasiswa sudah percaya JastipKu. Giliran kamu!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/order" class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 font-bold px-10 py-4 rounded-2xl hover:bg-blue-50 transition-all shadow-xl">
                Pesan Jastip Sekarang
            </a>
            <a href="/register" class="inline-flex items-center justify-center gap-2 bg-blue-500/30 text-white font-semibold px-10 py-4 rounded-2xl hover:bg-blue-500/50 transition-all border border-blue-400">
                Daftar Jadi Jastiper
            </a>
        </div>
    </div>
</section>

@endsection
