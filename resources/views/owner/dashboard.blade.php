<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Dashboard Mitra
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    <i class="fa-solid fa-store text-orange-500 mr-1"></i> {{ $bengkel->nama_bengkel }}
                </p>
            </div>

            <div class="flex gap-3">
                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 text-center">
                    <span class="block text-xs text-gray-400 uppercase font-bold">Total Order</span>
                    <span class="text-lg font-bold text-gray-800">{{ $orders->count() }}</span>
                </div>
                <div class="bg-yellow-50 px-4 py-2 rounded-lg shadow-sm border border-yellow-100 text-center">
                    <span class="block text-xs text-yellow-600 uppercase font-bold">Menunggu</span>
                    <span class="text-lg font-bold text-yellow-700">{{ $orders->where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Pesanan Masuk</h3>
                    <button onclick="window.location.reload();" class="text-sm text-blue-600 hover:underline">
                        <i class="fa-solid fa-rotate-right"></i> Refresh
                    </button>
                </div>

                <div class="p-6 bg-gray-50/50">

                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <div class="bg-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <i class="fa-regular fa-folder-open text-3xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-600">Belum Ada Pesanan</h3>
                            <p class="text-sm text-gray-400">Pesanan dari pelanggan akan muncul di sini.</p>
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
                                    @endphp
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $statusColor }}"></div>

                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pl-3">

                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-lg font-bold text-gray-800">{{ $order->nama_pemesan }}</h4>
                                                <span class="text-xs px-2 py-0.5 rounded-full font-bold uppercase {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-700 animate-pulse' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </div>

                                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <i class="fa-regular fa-clock"></i> {{ $order->created_at->diffForHumans() }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i class="fa-solid {{ $order->jenis_kendaraan == 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i>
                                                    {{ ucfirst($order->jenis_kendaraan) }}
                                                </span>
                                            </div>

                                            <p class="text-sm text-gray-600 mt-2 flex items-start gap-1 max-w-lg">
                                                <i class="fa-solid fa-map-pin text-red-400 mt-1 shrink-0"></i>
                                                <span class="truncate">{{ Str::limit($order->alamat_lengkap, 60) }}</span>
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2 w-full md:w-auto">

                                            @if($order->status == 'proses')
                                                <a href="{{ route('chat.show', $order->id) }}" class="flex-1 md:flex-none bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2.5 rounded-lg font-bold text-sm transition text-center" title="Chat Pelanggan">
                                                    <i class="fa-regular fa-comments text-lg"></i>
                                                </a>
                                            @endif

                                            <a href="{{ route('owner.order.show', $order->id) }}" class="flex-1 md:flex-none bg-gray-900 text-white hover:bg-gray-800 px-6 py-2.5 rounded-lg font-bold text-sm transition shadow-sm flex items-center justify-center gap-2 group-hover:scale-105 transform duration-200">
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
