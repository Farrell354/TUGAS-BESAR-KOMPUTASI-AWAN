<x-app-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
        <div class="bg-white p-8 rounded-xl shadow-lg text-center max-w-md">
            <div class="bg-yellow-100 text-yellow-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-shop-lock text-4xl"></i>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Bengkel</h2>
            <p class="text-gray-500 mb-6">
                Akun Anda terdaftar sebagai <b>Owner</b>, namun belum terhubung ke lokasi bengkel manapun.
            </p>

            <div class="bg-blue-50 p-4 rounded-lg text-left text-sm text-blue-800 mb-6 border border-blue-100">
                <strong>Apa yang harus dilakukan?</strong>
                <ul class="list-disc ml-5 mt-2 space-y-1">
                    <li>Hubungi <b>Administrator</b> aplikasi.</li>
                    <li>Minta Admin untuk mengedit lokasi bengkel Anda.</li>
                    <li>Minta Admin memilih nama Anda di kolom "Owner".</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-500 font-bold hover:underline">Logout Sekarang</button>
            </form>
        </div>
    </div>
</x-app-layout>
