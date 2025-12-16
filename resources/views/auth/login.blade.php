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
                <h2 class="text-xl font-bold text-gray-800" x-text="role === 'user' ? 'Masuk sebagai Pengguna' : 'Login Mitra Bengkel'"></h2>
                <p class="text-sm text-gray-500 mt-1" x-text="role === 'user' ? 'Cari bantuan tambal ban sekarang.' : 'Kelola pesanan bengkel Anda.'"></p>
            </div>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" class="block w-full rounded-lg border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm sm:text-sm py-2.5 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" class="block w-full rounded-lg border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm sm:text-sm py-2.5 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex justify-between items-center">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 shadow-sm"
                           :class="role === 'owner' ? 'text-orange-600 focus:ring-orange-500' : 'text-blue-600 focus:ring-blue-500'"
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-xs font-medium hover:underline"
                       :class="role === 'owner' ? 'text-orange-600' : 'text-blue-600'"
                       href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white transition-all transform hover:-translate-y-0.5"
                :class="role === 'owner' ? 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'">
                <span x-text="role === 'owner' ? 'Masuk ke Dashboard' : 'Masuk Sekarang'"></span>
            </button>

            <div class="mt-4 text-center border-t pt-4">
                <p class="text-sm text-gray-600" x-show="role === 'user'">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:underline">Daftar Pengguna</a>
                </p>
                <p class="text-sm text-gray-600" x-show="role === 'owner'" style="display: none;">
                    Ingin menjadi mitra?
                    <span class="text-xs block text-gray-400">(Hubungi Admin untuk pendaftaran mitra)</span>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
