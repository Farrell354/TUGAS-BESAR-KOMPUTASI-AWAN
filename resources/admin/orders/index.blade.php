<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-clipboard-list text-blue-600"></i>
            Daftar Pesanan Masuk
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center">
                    <div>
                        <span class="font-bold">Berhasil!</span> {{ session('success') }}
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">x</button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Semua Transaksi</h3>
                    <span class="text-xs text-gray-400">Klik pada baris untuk melihat detail</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold tracking-wide text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Pelanggan</th>
                                <th class="px-4 py-3">Lokasi & Keluhan</th>
                                <th class="px-4 py-3">Bengkel Tujuan</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)

                            <tr onclick="window.location='{{ route('admin.orders.show', $order->id) }}'"
                                class="hover:bg-blue-50 cursor-pointer transition duration-150 group">

                                <td class="px-4 py-4 whitespace-nowrap">
                                    <p class="text-sm font-bold text-gray-700">{{ $order->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }} WIB</p>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="text-sm font-bold text-gray-800">{{ $order->nama_pemesan }}</p>

                                    <a href="https://wa.me/{{ $order->nomer_telepon }}" target="_blank" onclick="event.stopPropagation()"
                                       class="text-xs text-green-600 hover:underline flex items-center gap-1 mt-1 w-fit bg-green-50 px-2 py-0.5 rounded border border-green-100 hover:bg-green-100 transition">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $order->nomer_telepon }}
                                    </a>
                                </td>

                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->jenis_kendaraan == 'motor' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                                        <i class="fa-solid {{ $order->jenis_kendaraan == 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i>
                                        {{ $order->jenis_kendaraan }}
                                    </span>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-1 group-hover:text-blue-600 transition" title="{{ $order->alamat_lengkap }}">
                                        <i class="fa-solid fa-map-pin text-red-400"></i> {{ Str::limit($order->alamat_lengkap, 25) }}
                                    </p>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="text-sm font-medium text-gray-600">{{ $order->tambalBan->nama_bengkel }}</p>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'proses' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'selesai' => 'bg-green-100 text-green-800 border-green-200',
                                            'batal' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                        $labels = [
                                            'pending' => 'Menunggu',
                                            'proses' => 'Proses',
                                            'selesai' => 'Selesai',
                                            'batal' => 'Batal',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 font-bold text-xs rounded-full border {{ $colors[$order->status] }}">
                                        {{ $labels[$order->status] }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center gap-2">

                                        @if($order->status == 'pending')
                                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" onclick="event.stopPropagation()">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="proses">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white w-8 h-8 rounded-lg flex items-center justify-center transition shadow-sm" title="Proses Pesanan">
                                                    <i class="fa-solid fa-person-biking"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($order->status == 'proses')
                                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" onclick="event.stopPropagation()">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="selesai">
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white w-8 h-8 rounded-lg flex items-center justify-center transition shadow-sm" title="Selesaikan">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($order->status == 'pending' || $order->status == 'proses')
                                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?');" onclick="event.stopPropagation()">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="batal">
                                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 w-8 h-8 rounded-lg flex items-center justify-center transition border border-red-200" title="Batalkan">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($order->status == 'selesai' || $order->status == 'batal')
                                            <span class="text-gray-300"><i class="fa-solid fa-chevron-right"></i></span>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-gray-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-inbox text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-medium">Belum ada pesanan masuk.</p>
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
