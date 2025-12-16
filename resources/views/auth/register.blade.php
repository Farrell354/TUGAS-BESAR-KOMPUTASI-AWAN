<x-guest-layout>
    <div x-data="{ role: 'user' }">

        <div class="mb-6">
            <div class="flex justify-center mb-6">
                <div class="bg-gray-100 p-1 rounded-lg inline-flex shadow-inner">
                    <button @click="role = 'user'"
                        :class="role === 'user' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-6 py-2 rounded-md text-sm font-bold transition-all duration-200">
                        Pengguna
                    </button>
                    <button @click="role = 'owner'"
                        :class="role === 'owner' ? 'bg-white text-orange-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-6 py-2 rounded-md text-sm font-bold transition-all duration-200">
                        Mitra Bengkel
                    </button>
                </div>
            </div>

            <div class="text-center">
                <h2 class="text-xl font-bold text-gray-800" x-text="role === 'user' ? 'Daftar Akun Pengguna' : 'Daftar Jadi Mitra'"></h2>
                <p class="text-sm text-gray-500 mt-1" x-text="role === 'user' ? 'Temukan tukang tambal ban dengan cepat.' : 'Kelola bengkel dan terima pesanan online.'"></p>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="role" :value="role">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" x-text="role === 'user' ? 'Nama Lengkap' : 'Nama Pemilik Bengkel'"></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-gray-400"></i>
                    </div>
                    <input id="name" class="block w-full rounded-lg border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm sm:text-sm py-2.5 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="text" name="name" :value="old('name')" required autofocus placeholder="Nama Anda">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" class="block w-full rounded-lg border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm sm:text-sm py-2.5 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="email" name="email" :value="old('email')" required placeholder="email@contoh.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" class="block w-full rounded-lg border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm sm:text-sm py-2.5 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-check-double text-gray-400"></i>
                    </div>
                    <input id="password_confirmation" class="block w-full rounded-lg border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm sm:text-sm py-2.5 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="password" name="password_confirmation" required placeholder="Ulangi password">
                </div>
            </div>

            <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white transition-all transform hover:-translate-y-0.5"
                :class="role === 'owner' ? 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'">
                <span x-text="role === 'owner' ? 'Daftar Sebagai Mitra' : 'Daftar Sebagai Pengguna'"></span>
            </button>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                       class="font-bold hover:underline"
                       :class="role === 'owner' ? 'text-orange-600' : 'text-blue-600'">
                        Masuk disini
                    </a>
                </p>
            </div>
        </form>

    </div>
</x-guest-layout>
