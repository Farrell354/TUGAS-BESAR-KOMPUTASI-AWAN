<x-guest-layout>
    <div x-data="{ role: 'user' }" class="w-full">

        <div class="mb-8">
            <div class="flex justify-center mb-6">
                <div class="bg-gray-100 p-1.5 rounded-xl flex w-full max-w-xs shadow-inner relative">
                    <button @click="role = 'user'"
                        :class="role === 'user' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all duration-200 focus:outline-none">
                        Pengguna
                    </button>
                    <button @click="role = 'owner'"
                        :class="role === 'owner' ? 'bg-white text-orange-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all duration-200 focus:outline-none">
                        Mitra
                    </button>
                </div>
            </div>

            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900" x-text="role === 'user' ? 'Selamat Datang' : 'Portal Mitra'"></h2>
                <p class="text-sm text-gray-500 mt-2" x-text="role === 'user' ? 'Masuk untuk mencari bantuan tambal ban.' : 'Masuk untuk mengelola bengkel Anda.'"></p>
            </div>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" class="block w-full rounded-xl border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm text-sm py-3 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" class="block w-full rounded-xl border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm text-sm py-3 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex flex-wrap justify-between items-center gap-2">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 shadow-sm w-4 h-4"
                           :class="role === 'owner' ? 'text-orange-600 focus:ring-orange-500' : 'text-blue-600 focus:ring-blue-500'"
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium hover:underline transition-colors"
                       :class="role === 'owner' ? 'text-orange-600 hover:text-orange-700' : 'text-blue-600 hover:text-blue-700'"
                       href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white transition-all transform hover:-translate-y-0.5 active:scale-95 focus:outline-none focus:ring-4"
                    :class="role === 'owner' ? 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-200 shadow-orange-200' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-200 shadow-blue-200'">
                <span x-text="role === 'owner' ? 'Masuk Dashboard Mitra' : 'Masuk Sekarang'"></span>
            </button>

            <div class="mt-6 text-center pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600" x-show="role === 'user'">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1 transition">Daftar Pengguna</a>
                </p>
                <div x-show="role === 'owner'" style="display: none;">
                    <p class="text-sm text-gray-600">Ingin menjadi mitra bengkel?</p>
                    <p class="text-xs text-gray-400 mt-1">Silakan hubungi Admin untuk pendaftaran.</p>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>