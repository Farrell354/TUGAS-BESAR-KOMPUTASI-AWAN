<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-clipboard-list text-blue-600"></i>
            Daftar Pesanan Masuk
        </h2>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm flex justify-between items-center animate-fade-in-down">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check-circle text-green-500"></i>
                        <span><strong class="font-bold">Berhasil!</strong> {{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-700 font-bold hover:text-green-900">&times;</button>
                </div>
            @endif

            <div class="block md:hidden space-y-4">
                @forelse($orders as $order)
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 relative overflow-hidden" onclick="window.location='{{ route('admin.orders.show', $order->id) }}'">
                        
                        @php
                            $statusColor = match($order->status) {
                                'pending' => 'bg-yellow-400',
                                'proses' => 'bg-blue-500',
                                'selesai' => 'bg-green-500',
                                'batal' => 'bg-red-500',
                                default => 'bg-gray-300'
                            };
                        @endphp
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $statusColor }}"></div>

                        <div class="pl-3">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    <h3 class="font-bold text-gray-900 text-lg">{{ $order->nama_pemesan }}</h3>
                                </div>
                                <span class="px-2 py-1 text-[10px] font-bold uppercase rounded border {{ $order->status == 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : ($order->status == 'proses' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-gray-50 text-gray-600') }}">
                                    {{ $order->status }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-1 flex items-center gap-1">
                                <i class="fa-solid fa-store text-blue-400"></i> {{ $order->tambalBan->nama_bengkel }}
                            </p>
                            <p class="text-sm text-gray-500 mb-3 flex items-start gap-1">
                                <i class="fa-solid fa-map-pin text-red-400 mt-0.5"></i> 
                                <span class="line-clamp-1">{{ $order->alamat_lengkap }}</span>
                            </p>

                            <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100" onclick="event.stopPropagation()">
                                <a href="https://wa.me/{{ $order->nomer_telepon }}" target="_blank" class="flex-1 bg-green-50 text-green-600 border border-green-200 py-2 rounded-lg text-xs font-bold text-center flex items-center justify-center gap-1">
                                    <i class="fa-brands fa-whatsapp"></i> WA
                                </a>
                                @if($order->status == 'pending')
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="flex-1">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="proses">
                                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg text-xs font-bold shadow-sm">
                                            Proses
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="flex-1 bg-gray-100 text-gray-600 py-2 rounded-lg text-xs font-bold text-center">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 bg-white rounded-xl border border-dashed border-gray-300">
                        <p>Tidak ada pesanan.</p>
                    </div>
                @endforelse
            </div>

            <div class="hidden md:block bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Semua Transaksi</h3>
                    <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded border">Total: {{ $orders->count() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold tracking-wide text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Pelanggan</th>
                                <th class="px-6 py-3">Lokasi & Keluhan</th>
                                <th class="px-6 py-3">Bengkel Tujuan</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                            <tr onclick="window.location='{{ route('admin.orders.show', $order->id) }}'" class="hover:bg-blue-50 cursor-pointer transition duration-150 group">
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-bold text-gray-700">{{ $order->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }} WIB</p>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-800">{{ $order->nama_pemesan }}</p>
                                    <a href="https://wa.me/{{ $order->nomer_telepon }}" target="_blank" onclick="event.stopPropagation()" class="text-xs text-green-600 hover:underline flex items-center gap-1 mt-1 w-fit bg-green-50 px-2 py-0.5 rounded border border-green-100 hover:bg-green-100 transition">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $order->nomer_telepon }}
                                    </a>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->jenis_kendaraan == 'motor' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                                        <i class="fa-solid {{ $order->jenis_kendaraan == 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i> {{ $order->jenis_kendaraan }}
                                    </span>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-1 group-hover:text-blue-600 transition" title="{{ $order->alamat_lengkap }}">
                                        <i class="fa-solid fa-map-pin text-red-400"></i> {{ Str::limit($order->alamat_lengkap, 25) }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-600">{{ $order->tambalBan->nama_bengkel }}</p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'proses' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'selesai' => 'bg-green-100 text-green-800 border-green-200',
                                            'batal' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 font-bold text-xs rounded-full border {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2" onclick="event.stopPropagation()">
                                        @if($order->status == 'pending')
                                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="proses">
                                                <button class="bg-blue-600 hover:bg-blue-700 text-white w-8 h-8 rounded-lg flex items-center justify-center shadow-sm" title="Proses">
                                                    <i class="fa-solid fa-person-biking"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($order->status == 'proses')
                                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="selesai">
                                                <button class="bg-green-600 hover:bg-green-700 text-white w-8 h-8 rounded-lg flex items-center justify-center shadow-sm" title="Selesai">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($order->status == 'pending' || $order->status == 'proses')
                                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" onsubmit="return confirm('Batalkan?');">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="batal">
                                                <button class="bg-white border border-red-200 text-red-600 hover:bg-red-50 w-8 h-8 rounded-lg flex items-center justify-center" title="Batalkan">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if(in_array($order->status, ['selesai', 'batal']))
                                            <span class="text-gray-300"><i class="fa-solid fa-chevron-right"></i></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-inbox text-3xl text-gray-200 mb-2"></i>
                                        <p>Belum ada pesanan masuk.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>