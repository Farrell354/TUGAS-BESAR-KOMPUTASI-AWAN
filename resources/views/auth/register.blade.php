<x-guest-layout>
    <div x-data="{ role: 'user' }" class="w-full">

        <div class="mb-8">
            <div class="flex justify-center mb-6">
                <div class="bg-gray-100 p-1.5 rounded-xl flex w-full max-w-xs shadow-inner">
                    <button @click="role = 'user'"
                        :class="role === 'user' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all duration-200 focus:outline-none">
                        Pengguna
                    </button>
                    <button @click="role = 'owner'"
                        :class="role === 'owner' ? 'bg-white text-orange-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all duration-200 focus:outline-none">
                        Mitra Bengkel
                    </button>
                </div>
            </div>

            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900" x-text="role === 'user' ? 'Daftar Akun Baru' : 'Gabung Jadi Mitra'"></h2>
                <p class="text-sm text-gray-500 mt-2" x-text="role === 'user' ? 'Cari bantuan tambal ban dengan mudah.' : 'Kelola bengkel dan dapatkan pelanggan online.'"></p>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="role" :value="role">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" x-text="role === 'user' ? 'Nama Lengkap' : 'Nama Pemilik / Bengkel'"></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-gray-400"></i>
                    </div>
                    <input id="name" class="block w-full rounded-xl border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm text-sm py-3 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="text" name="name" :value="old('name')" required autofocus placeholder="Masukkan nama...">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" class="block w-full rounded-xl border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm text-sm py-3 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="email" name="email" :value="old('email')" required placeholder="nama@email.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" class="block w-full rounded-xl border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm text-sm py-3 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ulangi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-check-double text-gray-400"></i>
                    </div>
                    <input id="password_confirmation" class="block w-full rounded-xl border-gray-300 pl-10 focus:ring-opacity-50 shadow-sm text-sm py-3 transition"
                           :class="role === 'owner' ? 'focus:border-orange-500 focus:ring-orange-200' : 'focus:border-blue-500 focus:ring-blue-200'"
                           type="password" name="password_confirmation" required placeholder="Ketik ulang password">
                </div>
            </div>

            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white transition-all transform hover:-translate-y-0.5 active:scale-95 focus:outline-none focus:ring-4"
                :class="role === 'owner' ? 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-200 shadow-orange-200' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-200 shadow-blue-200'">
                <span x-text="role === 'owner' ? 'Daftar Mitra Bengkel' : 'Daftar Sekarang'"></span>
            </button>

            <div class="mt-6 text-center pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                       class="font-bold hover:underline transition-colors"
                       :class="role === 'owner' ? 'text-orange-600 hover:text-orange-700' : 'text-blue-600 hover:text-blue-700'">
                        Masuk disini
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>