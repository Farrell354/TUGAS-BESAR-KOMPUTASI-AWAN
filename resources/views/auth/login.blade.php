<x-guest-layout>
    <div x-data="{ role: 'user', showPassword: false }" class="w-full mt-4">

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

            <div class="text-center space-y-2">
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight"
                    x-text="role === 'user' ? 'Selamat Datang' : 'Portal Mitra'"></h2>
                <p class="text-sm text-gray-500"
                    x-text="role === 'user' ? 'Masuk untuk mencari bantuan tambal ban.' : 'Masuk untuk mengelola bengkel Anda.'">
                </p>
            </div>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gray-400 group-focus-within:text-gray-600 transition"></i>
                    </div>
                    <input id="email"
                        class="block w-full rounded-xl border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm text-sm py-3 transition"
                        :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        placeholder="nama@email.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400 group-focus-within:text-gray-600 transition"></i>
                    </div>
                    
                    <input id="password"
                        class="block w-full rounded-xl border-gray-300 pl-10 pr-10 focus:ring-opacity-50 shadow-sm text-sm py-3 transition"
                        :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                        :type="showPassword ? 'text' : 'password'" 
                        name="password" required autocomplete="current-password" placeholder="••••••••">

                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                        <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex flex-wrap justify-between items-center gap-2">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 shadow-sm w-4 h-4 transition"
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
        </form>

        <div class="relative mt-8 mb-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Atau lanjutkan dengan</span>
            </div>
        </div>

        <a href="{{ route('google.login') }}" 
           class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                    <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                    <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.424 63.239 -14.754 63.239 Z"/>
                    <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.734 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                    <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.799 L -6.734 42.379 C -8.804 40.439 -11.514 39.239 -14.754 39.239 C -19.424 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
                </g>
            </svg>
            Masuk dengan Google
        </a>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600" x-show="role === 'user'">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1 transition">
                    Daftar Pengguna
                </a>
            </p>
            <div x-show="role === 'owner'" style="display: none;">
                <p class="text-sm text-gray-600">Ingin menjadi mitra bengkel?</p>
                <p class="text-xs text-gray-400 mt-1">Silakan hubungi Admin untuk pendaftaran.</p>
            </div>
        </div>
    </div>
</x-guest-layout>