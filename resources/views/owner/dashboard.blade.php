<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Dashboard Mitra
                </h2>
                <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-store text-orange-500"></i> {{ $bengkel->nama_bengkel ?? 'Nama Bengkel' }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 w-full md:w-auto">
                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 text-center">
                    <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Total Order</span>
                    <span class="text-xl font-bold text-gray-800">{{ $orders->count() }}</span>
                </div>
                <div class="bg-yellow-50 px-4 py-2 rounded-lg shadow-sm border border-yellow-100 text-center">
                    <span class="block text-[10px] text-yellow-600 uppercase font-bold tracking-wider">Menunggu</span>
                    <span class="text-xl font-bold text-yellow-700">{{ $orders->where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-lg p-6 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
                <i class="fa-solid fa-screwdriver-wrench absolute -right-6 -bottom-6 text-8xl text-white/10 rotate-12 pointer-events-none"></i>
                
                <div class="relative z-10">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-wrench"></i> Kelola Profil Bengkel
                    </h3>
                    <p class="text-blue-100 text-sm mt-2 max-w-xl leading-relaxed">
                        Atur harga jasa, ubah lokasi, update foto, dan perbarui jam operasional agar pelanggan mendapatkan informasi yang akurat.
                    </p>
                </div>
                <a href="{{ route('owner.bengkel.edit') }}" class="relative z-10 w-full md:w-auto bg-white text-blue-700 hover:bg-blue-50 font-bold py-3 px-6 rounded-lg shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-gear"></i> Atur Profil & Tarif
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-inbox text-blue-500"></i> Pesanan Masuk
                    </h3>
                    <button onclick="window.location.reload();" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-lg transition">
                        <i class="fa-solid fa-rotate-right"></i> Refresh
                    </button>
                </div>

                <div class="p-4 md:p-6 bg-gray-50/30">

                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <div class="bg-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                                <i class="fa-regular fa-folder-open text-3xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-600">Belum Ada Pesanan</h3>
                            <p class="text-sm text-gray-400 mt-1">Pesanan dari pelanggan akan muncul di sini secara real-time.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition duration-200 relative overflow-hidden group">

                                    @php
                                        $statusColor = match($order->status) {
                                            'pending' => 'bg-yellow-400',
                                            'proses' => 'bg-blue-500',
                                            'selesai' => 'bg-green-500',
                                            'batal' => 'bg-red-500',
                                            default => 'bg-gray-300'
                                        };
                                        $statusLabel = match($order->status) {
                                            'pending' => 'Menunggu Konfirmasi',
                                            'proses' => 'Sedang Diproses',
                                            'selesai' => 'Selesai',
                                            'batal' => 'Dibatalkan',
                                            default => ucfirst($order->status)
                                        };
                                        $badgeClass = match($order->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-700 animate-pulse',
                                            'proses' => 'bg-blue-100 text-blue-700',
                                            'selesai' => 'bg-green-100 text-green-700',
                                            'batal' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-600'
                                        };
                                    @endphp
                                    
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $statusColor }}"></div>

                                    <div class="flex flex-col md:flex-row justify-between gap-4 pl-3">

                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                                <h4 class="text-lg font-bold text-gray-800">{{ $order->nama_pemesan }}</h4>
                                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase border border-transparent {{ $badgeClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </div>

                                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs font-medium text-gray-500 mb-3">
                                                <span class="flex items-center gap-1">
                                                    <i class="fa-regular fa-clock"></i> {{ $order->created_at->diffForHumans() }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i class="fa-solid {{ $order->jenis_kendaraan == 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i> 
                                                    {{ ucfirst($order->jenis_kendaraan) }}
                                                </span>
                                            </div>

                                            <p class="text-sm text-gray-600 flex items-start gap-1.5 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                                <i class="fa-solid fa-map-pin text-red-500 mt-0.5 shrink-0"></i>
                                                <span class="line-clamp-2 leading-snug">{{ $order->alamat_lengkap }}</span>
                                            </p>
                                        </div>

                                        <div class="flex flex-row md:flex-col items-center md:items-end justify-end gap-2 mt-2 md:mt-0 pt-3 md:pt-0 border-t md:border-t-0 border-gray-100">

                                            @if($order->status == 'proses')
                                                <a href="{{ route('chat.show', $order->id) }}" class="flex-1 md:flex-none w-full md:w-auto bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 px-4 py-2 rounded-lg font-bold text-xs transition text-center flex items-center justify-center gap-1" title="Chat Pelanggan">
                                                    <i class="fa-regular fa-comments text-base"></i> <span class="md:hidden">Chat</span>
                                                </a>
                                            @endif

                                            <a href="{{ route('owner.order.show', $order->id) }}" class="flex-1 md:flex-none w-full md:w-auto bg-gray-900 text-white hover:bg-gray-800 px-5 py-2.5 rounded-lg font-bold text-xs transition shadow-md flex items-center justify-center gap-2 group-hover:scale-105 transform duration-200">
                                                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
                                            </a>

                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>