<x-app-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 p-4">
        
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl text-center w-full max-w-md border border-gray-100 relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-2 bg-yellow-400"></div>

            <div class="relative">
                <div class="bg-yellow-50 text-yellow-600 w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-yellow-100">
                    <i class="fa-solid fa-shop-lock text-3xl md:text-4xl"></i>
                </div>
                <span class="absolute top-0 right-[35%] flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            </div>

            <h2 class="text-xl md:text-2xl font-extrabold text-gray-800 mb-2">Belum Ada Bengkel</h2>
            <p class="text-sm md:text-base text-gray-500 mb-8 leading-relaxed">
                Halo, akun Anda terdaftar sebagai <span class="font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded text-xs border border-gray-200">MITRA</span>, namun belum terhubung ke lokasi fisik bengkel manapun.
            </p>

            <div class="bg-blue-50 p-5 rounded-xl text-left text-sm text-blue-800 mb-8 border border-blue-100 relative">
                <div class="absolute -top-3 -left-3 bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-md">
                    <i class="fa-solid fa-info text-xs"></i>
                </div>

                <p class="font-bold mb-2 flex items-center gap-2">
                    Langkah Selanjutnya:
                </p>
                <ul class="list-disc ml-5 space-y-1.5 text-blue-700/80 text-xs md:text-sm">
                    <li>Hubungi <b>Administrator</b> aplikasi.</li>
                    <li>Minta Admin menambahkan data bengkel Anda.</li>
                    <li>Pastikan Admin memilih akun Anda sebagai Owner.</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-bold py-3 rounded-xl shadow-sm transition transform active:scale-95 flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket text-red-500"></i> Logout Sekarang
                </button>
            </form>

        </div>

        <p class="mt-8 text-xs text-gray-400 font-medium">
            &copy; {{ date('Y') }} TambalFinder System
        </p>

    </div>
</x-app-layout>