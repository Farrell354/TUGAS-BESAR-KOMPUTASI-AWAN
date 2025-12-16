<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TambalFinder - Solusi Ban Bocor Tercepat</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }

        /* Hero Background */
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1487754180477-9c8329ea4270?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .hero-overlay { background: linear-gradient(to right, rgba(17, 24, 39, 0.9), rgba(17, 24, 39, 0.4)); }
    </style>
</head>
<body class="antialiased bg-white">

    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 py-4 bg-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex justify-between items-center">

            <div class="flex items-center gap-2">
                <div class="bg-blue-600 text-white p-2 rounded-lg shadow-lg">
                    <i class="fa-solid fa-map-location-dot text-xl"></i>
                </div>
                <span id="navLogoText" class="text-white text-xl font-bold tracking-tight">Tambal<span class="text-blue-500">Finder</span></span>
            </div>

            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <span class="nav-text text-white/80 text-sm hidden md:inline border-r border-white/20 pr-4 mr-2 font-medium">
                            Halo, {{ Auth::user()->name }}
                        </span>

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ url('/admin/dashboard') }}" class="nav-link text-white font-semibold hover:text-blue-400 transition">
                                Dashboard
                            </a>

                        @elseif(Auth::user()->role === 'owner')
                            <a href="{{ route('owner.dashboard') }}" class="nav-link text-white font-semibold hover:text-blue-400 transition">
                                Dashboard Mitra
                            </a>

                        @else
                            <a href="{{ route('booking.history') }}" class="nav-link text-white font-medium hover:text-blue-300 transition flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-btn-outline bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium transition border border-white/20">
                                Keluar
                            </button>
                        </form>

                    @else
                        <a href="{{ route('login') }}" class="nav-link text-white font-medium hover:text-blue-300 transition hidden sm:block">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-bold transition shadow-lg transform hover:scale-105 border border-blue-500">
                            Daftar Sekarang
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <header class="hero-bg h-screen flex items-center relative">
        <div class="hero-overlay absolute inset-0"></div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 w-full pt-16">
            <div class="max-w-2xl">
                <span class="text-blue-400 font-bold tracking-wider uppercase text-sm mb-2 block">Solusi Darurat Jalan Raya</span>
                <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight leading-tight mb-6">
                    Ban Bocor? <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-teal-400">Kami Datang.</span>
                </h1>
                <p class="text-lg text-gray-300 mb-8 leading-relaxed">
                    Temukan tukang tambal ban terdekat dalam hitungan detik atau panggil mekanik langsung ke lokasi Anda. Cepat, aman, dan transparan.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('peta.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-lg px-8 py-4 rounded-xl font-bold transition flex items-center justify-center gap-3 shadow-lg shadow-blue-900/50 transform hover:-translate-y-1">
                        <i class="fa-solid fa-location-crosshairs"></i> Cari Sekarang
                    </a>
                    <a href="#features" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white text-lg px-8 py-4 rounded-xl font-bold transition border border-white/20 flex items-center justify-center">
                        Pelajari Fitur
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-blue-600 font-bold text-sm uppercase tracking-widest mb-2">Kenapa Memilih Kami?</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-gray-900">Teknologi Canggih untuk Kondisi Darurat</h3>
                <p class="text-gray-500 mt-4 text-lg">Kami menghubungkan Anda dengan jaringan bengkel terpercaya di sekitar Anda dengan fitur modern.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="group p-8 bg-gray-50 rounded-2xl hover:bg-blue-600 transition duration-300 hover:shadow-xl hover:-translate-y-2 border border-gray-100">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:bg-white group-hover:text-blue-600 transition">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-white">Peta Interaktif</h4>
                    <p class="text-gray-500 text-sm leading-relaxed group-hover:text-blue-100">
                        Cari lokasi tambal ban terdekat secara real-time dengan integrasi GPS yang akurat.
                    </p>
                </div>

                <div class="group p-8 bg-gray-50 rounded-2xl hover:bg-blue-600 transition duration-300 hover:shadow-xl hover:-translate-y-2 border border-gray-100">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:bg-white group-hover:text-blue-600 transition">
                        <i class="fa-solid fa-motorcycle"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-white">Jasa Panggilan</h4>
                    <p class="text-gray-500 text-sm leading-relaxed group-hover:text-blue-100">
                        Tidak perlu mendorong motor! Pesan lewat aplikasi, mekanik akan datang ke lokasi Anda.
                    </p>
                </div>

                <div class="group p-8 bg-gray-50 rounded-2xl hover:bg-blue-600 transition duration-300 hover:shadow-xl hover:-translate-y-2 border border-gray-100">
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:bg-white group-hover:text-blue-600 transition">
                        <i class="fa-regular fa-comments"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-white">Live Chat</h4>
                    <p class="text-gray-500 text-sm leading-relaxed group-hover:text-blue-100">
                        Komunikasi langsung dengan pemilik bengkel untuk memastikan ketersediaan dan estimasi waktu.
                    </p>
                </div>

                <div class="group p-8 bg-gray-50 rounded-2xl hover:bg-blue-600 transition duration-300 hover:shadow-xl hover:-translate-y-2 border border-gray-100">
                    <div class="w-14 h-14 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:bg-white group-hover:text-blue-600 transition">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-white">Bayar Tunai (COD)</h4>
                    <p class="text-gray-500 text-sm leading-relaxed group-hover:text-blue-100">
                        Sistem pembayaran yang aman dan mudah. Bayar langsung tunai setelah jasa selesai dikerjakan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600 rounded-full blur-[150px] opacity-20 -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600 rounded-full blur-[150px] opacity-20 translate-y-1/2 -translate-x-1/2"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Cara Kerja TambalFinder</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Tiga langkah mudah untuk mendapatkan bantuan saat ban bocor di jalan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center relative">
                <div class="hidden md:block absolute top-12 left-[20%] right-[20%] h-0.5 bg-gray-700 -z-10"></div>

                <div class="relative">
                    <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-gray-900 shadow-xl relative z-10">
                        <span class="text-3xl font-bold text-blue-500">1</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Tentukan Lokasi</h3>
                    <p class="text-gray-400 text-sm px-4">Buka peta dan biarkan GPS menemukan lokasi Anda, atau cari bengkel terdekat secara manual.</p>
                </div>

                <div class="relative">
                    <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-gray-900 shadow-xl relative z-10">
                        <span class="text-3xl font-bold text-blue-500">2</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Pesan Jasa</h3>
                    <p class="text-gray-400 text-sm px-4">Pilih "Pesan Jasa ke Rumah", isi detail kendaraan, dan kirim pesanan ke bengkel.</p>
                </div>

                <div class="relative">
                    <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-gray-900 shadow-xl relative z-10">
                        <span class="text-3xl font-bold text-blue-500">3</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Tunggu & Bayar</h3>
                    <p class="text-gray-400 text-sm px-4">Pantau status pesanan, chat dengan pemilik bengkel, dan bayar tunai saat selesai.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-blue-600">
        <div class="max-w-4xl mx-auto px-6 text-center text-white">
            <h2 class="text-3xl font-bold mb-6">Siap Menggunakan TambalFinder?</h2>
            <p class="text-blue-100 mb-8 text-lg">Jangan biarkan perjalanan Anda terganggu. Bergabunglah sekarang dan rasakan kemudahannya.</p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">

                @auth
                    <a href="{{ route('peta.index') }}" class="bg-white text-blue-600 px-10 py-3.5 rounded-full font-bold shadow-lg hover:bg-gray-100 transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-map-location-dot"></i> Buka Aplikasi
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-full font-bold shadow-lg hover:bg-gray-100 transition transform hover:-translate-y-1">
                        Daftar Akun Gratis
                    </a>

                    <a href="{{ route('peta.index') }}" class="bg-blue-700 text-white px-8 py-3 rounded-full font-bold border border-blue-400 hover:bg-blue-800 transition">
                        Lihat Peta Dulu
                    </a>
                @endauth

            </div>
        </div>
    </section>

    <footer class="bg-gray-50 py-10 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 text-white p-1.5 rounded">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <span class="font-bold text-gray-800 text-lg">TambalFinder</span>
            </div>
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} TambalFinder GIS. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // 1. Logic Scroll Navbar (Ubah warna teks & background)
        const navbar = document.getElementById('navbar');
        const navLogoText = document.getElementById('navLogoText');
        const navTexts = document.querySelectorAll('.nav-text');
        const navLinks = document.querySelectorAll('.nav-link');
        const navOutlines = document.querySelectorAll('.nav-btn-outline');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                // Mode Scroll (Putih)
                navbar.classList.remove('bg-transparent', 'py-4');
                navbar.classList.add('bg-white/95', 'backdrop-blur-md', 'shadow-md', 'py-2');

                navLogoText.classList.remove('text-white');
                navLogoText.classList.add('text-gray-800');

                navTexts.forEach(el => { el.classList.remove('text-white/80', 'border-white/20'); el.classList.add('text-gray-500', 'border-gray-300'); });
                navLinks.forEach(el => { el.classList.remove('text-white', 'hover:text-blue-300'); el.classList.add('text-gray-700', 'hover:text-blue-600'); });
                navOutlines.forEach(el => { el.classList.remove('bg-white/10', 'text-white', 'border-white/20', 'hover:bg-white/20'); el.classList.add('bg-transparent', 'text-red-600', 'border-red-200', 'hover:bg-red-50'); });

            } else {
                // Mode Top (Transparan)
                navbar.classList.add('bg-transparent', 'py-4');
                navbar.classList.remove('bg-white/95', 'backdrop-blur-md', 'shadow-md', 'py-2');

                navLogoText.classList.add('text-white');
                navLogoText.classList.remove('text-gray-800');

                navTexts.forEach(el => { el.classList.add('text-white/80', 'border-white/20'); el.classList.remove('text-gray-500', 'border-gray-300'); });
                navLinks.forEach(el => { el.classList.add('text-white', 'hover:text-blue-300'); el.classList.remove('text-gray-700', 'hover:text-blue-600'); });
                navOutlines.forEach(el => { el.classList.add('bg-white/10', 'text-white', 'border-white/20', 'hover:bg-white/20'); el.classList.remove('bg-transparent', 'text-red-600', 'border-red-200', 'hover:bg-red-50'); });
            }
        });

        // 2. Logic SweetAlert (Toast Notification)
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                let navigationEntries = performance.getEntriesByType("navigation");
                if (navigationEntries.length > 0 && navigationEntries[0].type === 'back_forward') {
                    return;
                }

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif
        });
    </script>
</body>
</html>
