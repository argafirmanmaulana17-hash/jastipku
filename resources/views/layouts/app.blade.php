<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JastipKu - Jasa Titip Kampus')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CDN -->
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

            0%,
            100% {
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
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-proses {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-otw {
            background: #ede9fe;
            color: #5b21b6;
        }

        .badge-selesai {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-batal {
            background: #fee2e2;
            color: #991b1b;
        }

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

<body class="bg-slate-50 text-slate-800">

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- LOGO -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
    
    <img src="{{ asset('images/logo1.png') }}" alt="Logo JastipKu" class="h-10 w-10 object-cover rounded-xl shadow-sm">
    
    <span class="font-display text-xl font-bold text-slate-900 tracking-tight">
        Jastip<span class="text-blue-600">Ku</span>
    </span>

</a>
                    

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="/"
                        class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Beranda</a>
                    <a href="/order"
                        class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Pesan
                        Jastip</a>
                    <a href="/tracking"
                        class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Tracking</a>
                    <a href="{{ route('faq') }}"
                        class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Bantuan / FAQ</a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="/dashboard"
                            class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Dashboard</a>
                             <a href="{{ route('user.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">  Riwayat Pesanan</a>
                        
                            
                            
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-sm font-medium bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="/login"
                            class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Masuk</a>
                        <a href="/register"
                            class="text-sm font-semibold bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">Daftar</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-slate-100 px-4 py-4 space-y-2">
            <a href="/" class="block py-2 text-sm font-medium text-slate-600">Beranda</a>
            <a href="/order" class="block py-2 text-sm font-medium text-slate-600">Pesan Jastip</a>
            <a href="/tracking" class="block py-2 text-sm font-medium text-slate-600">Tracking</a>
            <a href="/chat" class="block py-2 text-sm font-medium text-slate-600">Chat Kami</a>
            <div class="pt-2 border-t border-slate-100 flex gap-3">
                <a href="/login"
                    class="flex-1 text-center py-2 text-sm font-medium border border-slate-200 rounded-lg">Masuk</a>
                <a href="/register"
                    class="flex-1 text-center py-2 text-sm font-semibold bg-blue-600 text-white rounded-lg">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-400 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="font-display text-lg font-bold text-white">JastipKu</span>
                    </div>
                    <p class="text-sm leading-relaxed">Layanan jasa titip terpercaya khusus untuk mahasiswa kampus.
                        Pesan apa saja, kami antarkan!</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4">Layanan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/order" class="hover:text-white transition-colors">Pesan Jastip</a></li>
                        <li><a href="/tracking" class="hover:text-white transition-colors">Tracking Order</a></li>
                        <li><a href="/chat" class="hover:text-white transition-colors">Chat Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li>📱 WhatsApp: 0812-xxxx-xxxx</li>
                        <li>📧 jastipku@email.com</li>
                        <li>📍 Kampus Universitas Anda</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-8 text-center text-xs">
                <p>© 2025 JastipKu. Dibuat dengan ❤️ untuk mahasiswa.</p>
            </div>
        </div>
    </footer>

    <!-- Tawk.to Live Chat -->
    <script type="text/javascript">
        // Ganti dengan script Tawk.to kamu
        // var Tawk_API=Tawk_API||{}, Tawk_LoadTime=new Date();
        // (function(){ var s1=document.createElement("script"), s0=document.getElementsByTagName("script")[0];
        // s1.async=true; s1.src='https://embed.tawk.to/YOUR_ID/default';
        // s1.charset='UTF-8'; s1.setAttribute('crossorigin','*');
        // s0.parentNode.insertBefore(s1,s0); })();
    </script>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('bg-white/90', 'navbar-scroll', 'shadow-sm');
            } else {
                navbar.classList.remove('bg-white/90', 'navbar-scroll', 'shadow-sm');
            }
        });

        // Mobile menu
        document.getElementById('mobileMenuBtn').addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>

    @yield('scripts')
</body>

</html>