<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JastipKu - Jasa Titip Kampus')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Syne', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-display {
            font-family: 'Syne', sans-serif;
        }

        /* Navbar scroll effect */
        .navbar-scroll {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #2563eb, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card hover */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.15);
        }

        /* Sidebar active */
        .sidebar-link.active {
            background: #2563eb;
            color: white;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Status badges */
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-proses { background: #dbeafe; color: #1e40af; }
        .badge-otw { background: #ede9fe; color: #5b21b6; }
        .badge-selesai { background: #d1fae5; color: #065f46; }
        .badge-batal { background: #fee2e2; color: #991b1b; }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #93c5fd;
            border-radius: 3px;
        }
    </style>

    @yield('styles')
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 transform translate-y-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <a href="{{ auth()->user()?->role === 'jastiper' ? route('dashboard.jastiper.index') : (auth()->user()?->role === 'admin' ? route('dashboard.admin.index') : route('home')) }}" 
                   class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <img src="{{ asset('images/logo1.png') }}" alt="Logo JastipKu" class="h-10 w-10 object-cover rounded-xl shadow-sm">
                    <span class="font-display text-xl font-bold text-slate-900 tracking-tight">
                        Jastip<span class="text-blue-600">Ku</span>
                    </span>
                </a>

                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ auth()->user()?->role === 'jastiper' ? route('dashboard.jastiper.index') : (auth()->user()?->role === 'admin' ? route('dashboard.admin.index') : route('home')) }}" 
                       class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">Beranda</a>   
                    
                    <a href="/order" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Pesan Jastip</a>
                    <a href="/tracking" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Tracking</a>
                    <a href="{{ route('faq') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Bantuan / FAQ</a>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="/dashboard" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Dashboard</a>
                        <a href="/tracking" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors px-3 py-1.5 rounded-lg hover:bg-slate-100">Riwayat Pesanan</a>
                        
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Masuk</a>
                        <a href="/register" class="text-sm font-semibold bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">Daftar</a>
                    @endauth
                </div>

                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-slate-100 px-4 py-4 space-y-2 shadow-inner">
            @auth
                @if(auth()->user()->role === 'jastiper')
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 px-2 pt-1">Menu Kerja Jastiper</div>
                    <a href="{{ route('dashboard.jastiper.index') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all flex items-center gap-2">📊 Overview</a>
                    <a href="{{ route('dashboard.jastiper.orders') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all flex items-center gap-2">📥 Order Masuk</a>
                    <a href="{{ route('dashboard.jastiper.history') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all flex items-center gap-2">📜 Riwayat Kerja</a>
                    <a href="{{ route('dashboard.jastiper.earnings') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all flex items-center gap-2">💰 Penghasilan</a>
                    <a href="{{ route('dashboard.jastiper.profile') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all flex items-center gap-2">👤 Profil Jastiper</a>
                @elseif(auth()->user()->role === 'admin')
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 px-2 pt-1">Menu Panel Admin</div>
                    <a href="{{ route('dashboard.admin.index') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-blue-50 transition-all">📊 Main Dashboard</a>
                    <a href="{{ route('dashboard.admin.orders') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-blue-50 transition-all">📦 Semua Orderan</a>
                    <a href="{{ route('dashboard.admin.jastipers') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-blue-50 transition-all">👥 Data Jastiper</a>
                @else
                    <a href="/order" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-slate-50 flex items-center gap-2">🛍️ Pesan Jastip Baru</a>
                    <a href="/tracking" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-slate-50 flex items-center gap-2">🎯 Lacak & Riwayat Belanja</a>
                @endif
                
                <div class="pt-4 mt-2 border-t border-slate-100 flex gap-3">
                    <a href="/dashboard" class="flex-1 text-center py-2 text-sm font-semibold border border-slate-200 rounded-xl bg-slate-50 text-slate-700">Dashboard</a>
                    <form method="POST" action="/logout" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full text-center py-2 text-sm font-semibold bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors">Logout</button>
                    </form>
                </div>
            @else
                <a href="/order" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-slate-50">🛍️ Pesan Jastip</a>
                <a href="/tracking" class="block py-2.5 px-3 text-sm font-medium rounded-xl text-slate-700 hover:bg-slate-50">🎯 Lacak Pesanan</a>
                
                <div class="pt-4 mt-2 border-t border-slate-100 flex gap-3">
                    <a href="/login" class="flex-1 text-center py-2 text-sm font-semibold border border-slate-200 rounded-xl text-slate-700">Masuk</a>
                    <a href="/register" class="flex-1 text-center py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl shadow-md shadow-blue-100">Daftar</a>
                </div>
            @endauth
        </div>
    </nav>

    <main class="pt-24 min-h-screen bg-slate-50/50">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-400 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="font-display text-lg font-bold text-white">JastipKu</span>
                    </div>
                    <p class="text-sm leading-relaxed">Layanan jasa titip terpercaya khusus untuk mahasiswa kampus. Pesan apa saja, kami antarkan!</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4">Layanan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/order" class="hover:text-white transition-colors">Pesan Jastip</a></li>
                        <li><a href="/tracking" class="hover:text-white transition-colors">Tracking Order</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li>📱 WhatsApp: 0812-xxxx-xxxx</li>
                        <li>📧 jastipku@email.com</li>
                        <li>📍 Lingkungan Kampus</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-8 text-center text-xs">
                <p>© {{ date('Y') }} JastipKu. Dibuat dengan ❤️ untuk mahasiswa.</p>
            </div>
        </div>
    </footer>

    <script>
        let lastScrollTop = 0;
        const navbar = document.getElementById('navbar');
        const mobileMenu = document.getElementById('mobileMenu');

        window.addEventListener('scroll', () => {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // 1. Efek Tambah Shadow & Blur saat mulai Scroll
            if (scrollTop > 20) {
                navbar.classList.add('bg-white/90', 'navbar-scroll', 'shadow-sm');
            } else {
                navbar.classList.remove('bg-white/90', 'navbar-scroll', 'shadow-sm');
            }

            // 2. Efek Hilang-Muncul Otomatis (Smart Hide)
            if (scrollTop > lastScrollTop && scrollTop > 64) {
                // Scroll ke bawah: sembunyikan navbar (dorong keluar layar)
                navbar.style.transform = 'translateY(-100%)';
                mobileMenu.classList.add('hidden'); // Tutup mobile menu demi space
            } else {
                // Scroll ke atas: panggil kembali navbarmu
                navbar.style.transform = 'translateY(0)';
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });

        // Toggle Hamburger Mobile Menu
        document.getElementById('mobileMenuBtn').addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>

    @yield('scripts')
</body>

</html>