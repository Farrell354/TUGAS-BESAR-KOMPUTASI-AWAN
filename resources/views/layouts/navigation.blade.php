<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">

                @if(Auth::user()->role === 'admin')

                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                            <div class="bg-blue-600 text-white p-1.5 rounded-lg shadow-sm">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <span class="font-bold text-xl text-gray-900">Tambal<span class="text-blue-600">Finder</span></span>
                            <span class="text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full border border-gray-200 uppercase tracking-wider">Admin</span>
                        </a>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="group flex items-center gap-2">
                            <i class="fa-solid fa-table-columns text-gray-400 group-hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}"></i>
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.map')" :active="request()->routeIs('admin.map')" class="group flex items-center gap-2">
                            <i class="fa-solid fa-map text-gray-400 group-hover:text-blue-600 {{ request()->routeIs('admin.map') ? 'text-blue-600' : '' }}"></i>
                            {{ __('Live Map') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.index')" class="group flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-list text-gray-400 group-hover:text-blue-600 {{ request()->routeIs('admin.orders.index') ? 'text-blue-600' : '' }}"></i>
                            {{ __('Pesanan') }}
                        </x-nav-link>
                    </div>

                @elseif(Auth::user()->role === 'owner')

                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                            <div class="bg-orange-500 text-white p-1.5 rounded-lg shadow-sm">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                            <span class="font-bold text-xl text-gray-900">Tambal<span class="text-orange-500">Mitra</span></span>
                        </a>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard') || request()->routeIs('owner.order.show')" class="group flex items-center gap-2">
                            <i class="fa-solid fa-shop text-gray-400 group-hover:text-orange-600 {{ request()->routeIs('owner.dashboard') ? 'text-orange-600' : '' }}"></i>
                            {{ __('Dashboard Order') }}
                        </x-nav-link>
                    </div>

                @else

                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('landing') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                            <div class="bg-green-500 text-white p-1.5 rounded-lg shadow-sm">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                            <span class="font-bold text-xl text-gray-900">Tambal<span class="text-green-600">Finder</span></span>
                        </a>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                        <x-nav-link :href="route('landing')" :active="request()->routeIs('landing')" class="group flex items-center gap-2">
                            <i class="fa-solid fa-house text-gray-400 group-hover:text-green-600 {{ request()->routeIs('landing') ? 'text-green-600' : '' }}"></i>
                            {{ __('Home') }}
                        </x-nav-link>

                        <x-nav-link :href="route('peta.index')"
                                    :active="request()->routeIs('peta.index') || request()->routeIs('booking.create')"
                                    class="group flex items-center gap-2">
                            <i class="fa-solid fa-map-location text-gray-400 group-hover:text-green-600 {{ (request()->routeIs('peta.index') || request()->routeIs('booking.create')) ? 'text-green-600' : '' }}"></i>
                            {{ __('Cari Bengkel') }}
                        </x-nav-link>

                        <x-nav-link :href="route('booking.history')"
                                    :active="request()->routeIs('booking.history') || request()->routeIs('booking.show')"
                                    class="group flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-gray-400 group-hover:text-green-600 {{ (request()->routeIs('booking.history') || request()->routeIs('booking.show')) ? 'text-green-600' : '' }}"></i>
                            {{ __('Riwayat') }}
                        </x-nav-link>
                    </div>

                @endif
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-500 bg-gray-50 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition ease-in-out duration-150 gap-2">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center font-bold text-white shadow-sm
                                {{ Auth::user()->role === 'admin' ? 'bg-blue-600' : (Auth::user()->role === 'owner' ? 'bg-orange-500' : 'bg-green-600') }}">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs font-medium text-gray-500 uppercase">Login sebagai</p>
                            <p class="text-sm font-bold text-gray-900 capitalize">{{ Auth::user()->role }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fa-regular fa-id-card mr-2 text-gray-400"></i> {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 hover:bg-red-50">
                                <i class="fa-solid fa-right-from-bracket mr-2 text-red-400"></i> {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition">
                    <i :class="{'hidden': open, 'inline-flex': ! open }" class="fa-solid fa-bars text-xl"></i>
                    <i :class="{'hidden': ! open, 'inline-flex': open }" class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">

            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <i class="fa-solid fa-table-columns mr-2 opacity-70"></i> {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.map')" :active="request()->routeIs('admin.map')">
                    <i class="fa-solid fa-map mr-2 opacity-70"></i> {{ __('Live Map') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.index')">
                    <i class="fa-solid fa-clipboard-list mr-2 opacity-70"></i> {{ __('Pesanan') }}
                </x-responsive-nav-link>

            @elseif(Auth::user()->role === 'owner')
                <x-responsive-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard')">
                    <i class="fa-solid fa-shop mr-2 opacity-70"></i> {{ __('Dashboard') }}
                </x-responsive-nav-link>

            @else
                <x-responsive-nav-link :href="route('landing')" :active="request()->routeIs('landing')">
                    <i class="fa-solid fa-house mr-2 opacity-70"></i> {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('peta.index')" :active="request()->routeIs('peta.index')">
                    <i class="fa-solid fa-map-location mr-2 opacity-70"></i> {{ __('Cari Bengkel') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('booking.history')" :active="request()->routeIs('booking.history')">
                    <i class="fa-solid fa-clock-rotate-left mr-2 opacity-70"></i> {{ __('Riwayat') }}
                </x-responsive-nav-link>
            @endif

        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
            <div class="px-4 flex items-center gap-3 mb-3">
                <div class="h-10 w-10 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-blue-600' : (Auth::user()->role === 'owner' ? 'bg-orange-500' : 'bg-green-600') }} flex items-center justify-center text-white font-bold text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fa-regular fa-id-card mr-2"></i> {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
