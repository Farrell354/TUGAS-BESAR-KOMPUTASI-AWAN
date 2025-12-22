<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition shadow-sm shrink-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="overflow-hidden">
                <h2 class="font-bold text-lg md:text-xl text-gray-800 leading-tight truncate">
                    Order #{{ $order->kode_order ?? $order->id }}
                </h2>
                <p class="text-xs text-gray-500">Monitoring & Kontrol</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">

                @php
                    $statusStyles = [
                        'pending' => ['bg' => 'bg-yellow-500', 'icon' => 'fa-clock', 'label' => 'Menunggu Konfirmasi Owner'],
                        'proses' => ['bg' => 'bg-blue-600', 'icon' => 'fa-person-biking', 'label' => 'Sedang Diproses / OTW'],
                        'selesai' => ['bg' => 'bg-green-600', 'icon' => 'fa-check-circle', 'label' => 'Selesai'],
                        'batal' => ['bg' => 'bg-red-600', 'icon' => 'fa-circle-xmark', 'label' => 'Dibatalkan']
                    ];
                    $st = $statusStyles[$order->status] ?? $statusStyles['pending'];
                @endphp
                <div class="{{ $st['bg'] }} p-6 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-white/20 p-3 rounded-full shrink-0">
                            <i class="fa-solid {{ $st['icon'] }} text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase opacity-80 mb-1">Status Saat Ini</p>
                            <h3 class="text-xl md:text-2xl font-bold">{{ $st['label'] }}</h3>
                        </div>
                    </div>
                    <div class="w-full md:w-auto text-left md:text-right bg-white/10 px-4 py-2 rounded-lg">
                        <p class="text-xs opacity-75 uppercase font-bold">Waktu Order</p>
                        <p class="font-mono text-base md:text-lg">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div class="p-4 md:p-8 grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10">

                    <div class="lg:col-span-2 space-y-6 md:space-y-8">
                        
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Data Pelanggan</h4>
                            <div class="flex items-start gap-4 mb-4">
                                <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 text-xl shrink-0">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-lg">{{ $order->nama_pemesan }}</p>
                                    <div class="flex flex-wrap gap-3 text-sm mt-1">
                                        <a href="https://wa.me/{{ $order->nomer_telepon }}" target="_blank" class="text-green-600 hover:underline flex items-center gap-1 bg-green-50 px-2 py-0.5 rounded border border-green-100">
                                            <i class="fa-brands fa-whatsapp"></i> {{ $order->nomer_telepon }}
                                        </a>
                                        <span class="text-gray-400 hidden md:inline">|</span>
                                        <span class="text-gray-500">ID User: #{{ $order->user_id }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mb-4">
                                <p class="text-xs text-blue-600 font-bold uppercase mb-1 flex items-center gap-1">
                                    <i class="fa-solid fa-map-pin"></i> Lokasi Kejadian
                                </p>
                                <p class="text-gray-800 text-sm leading-relaxed">{{ $order->alamat_lengkap }}</p>
                            </div>

                            @if($order->latitude && $order->longitude)
                                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm relative group h-56 md:h-72 w-full">
                                    <div id="mapAdminDetail" class="w-full h-full z-0 bg-gray-100"></div>
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="absolute bottom-3 right-3 bg-white text-blue-600 text-xs font-bold px-3 py-2 rounded-lg shadow-md hover:bg-gray-50 transition flex items-center gap-2 z-[400]">
                                        <i class="fa-solid fa-diamond-turn-right"></i> Buka Maps
                                    </a>
                                </div>
                            @else
                                <div class="p-4 bg-yellow-50 text-yellow-700 text-sm rounded-lg border border-yellow-200 text-center">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Koordinat tidak tersedia.
                                </div>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Kendaraan & Keluhan</h4>
                            <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-white text-gray-800 font-bold text-sm border border-gray-200 shadow-sm">
                                        <i class="fa-solid {{ $order->jenis_kendaraan == 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i>
                                        {{ ucfirst($order->jenis_kendaraan) }}
                                    </span>
                                </div>
                                <p class="text-gray-500 text-xs uppercase font-bold mb-1">Keluhan:</p>
                                <p class="text-gray-700 italic">"{{ $order->keluhan }}"</p>
                            </div>

                            @if($order->foto_ban)
                                <div class="mt-4">
                                    <p class="text-xs text-gray-500 uppercase font-bold mb-2">Foto Kondisi Ban:</p>
                                    <div class="rounded-lg overflow-hidden border border-gray-200 w-full max-w-sm cursor-pointer group relative" onclick="window.open('{{ asset('storage/' . $order->foto_ban) }}', '_blank')">
                                        <img src="{{ asset('storage/' . $order->foto_ban) }}" alt="Foto Ban" class="w-full h-48 object-cover group-hover:opacity-90 transition">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 bg-black/30 transition">
                                            <span class="text-white text-xs font-bold bg-black/50 px-2 py-1 rounded backdrop-blur-sm">
                                                <i class="fa-solid fa-expand mr-1"></i> Perbesar
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-6">

                        <div class="bg-white p-5 rounded-xl shadow-md border border-blue-100 ring-4 ring-blue-50/50">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-sliders text-blue-600"></i> Kontrol Admin
                            </h3>

                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-3">
                                @csrf @method('PATCH')

                                <p class="text-xs text-gray-400 mb-2">Ubah status manual:</p>

                                @if($order->status == 'pending')
                                    <button name="status" value="proses" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg text-sm transition flex items-center justify-center gap-2 transform active:scale-95" onclick="return confirm('Paksa proses pesanan ini?')">
                                        <i class="fa-solid fa-person-biking"></i> Paksa Proses
                                    </button>
                                @endif

                                @if($order->status == 'proses')
                                    <button name="status" value="selesai" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg text-sm transition flex items-center justify-center gap-2 transform active:scale-95" onclick="return confirm('Tandai pesanan selesai?')">
                                        <i class="fa-solid fa-check"></i> Paksa Selesai
                                    </button>
                                @endif

                                @if($order->status != 'selesai' && $order->status != 'batal')
                                    <button name="status" value="batal" class="w-full bg-white hover:bg-red-50 text-red-600 border border-red-200 font-bold py-3 rounded-lg text-sm transition flex items-center justify-center gap-2 transform active:scale-95" onclick="return confirm('Yakin batalkan paksa?')">
                                        <i class="fa-solid fa-ban"></i> Batalkan Order
                                    </button>
                                @endif

                                @if($order->status == 'selesai' || $order->status == 'batal')
                                    <div class="text-center text-xs text-gray-400 py-3 bg-gray-50 rounded border border-gray-100">
                                        <i class="fa-solid fa-lock mr-1"></i> Status Final
                                    </div>
                                @endif
                            </form>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm">
                            <div class="flex items-start gap-3">
                                @if($order->metode_pembayaran == 'transfer')
                                    <i class="fa-solid fa-credit-card text-2xl text-blue-600 mt-1"></i>
                                @else
                                    <i class="fa-solid fa-wallet text-2xl text-green-600 mt-1"></i>
                                @endif
                                
                                <div class="w-full">
                                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Metode Pembayaran</p>
                                    
                                    @if($order->metode_pembayaran == 'transfer')
                                        <p class="font-bold text-blue-700 text-sm">Transfer / E-Wallet</p>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-xs text-gray-500">Status:</span>
                                            @if($order->payment_status == 'paid')
                                                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded border border-green-200 font-bold">LUNAS</span>
                                            @elseif($order->payment_status == 'unpaid')
                                                <span class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded border border-yellow-200 font-bold">BELUM BAYAR</span>
                                            @else
                                                <span class="text-[10px] bg-red-100 text-red-700 px-2 py-0.5 rounded border border-red-200 font-bold">GAGAL</span>
                                            @endif
                                        </div>
                                    @else
                                        <p class="font-bold text-gray-800 text-sm">Tunai (COD)</p>
                                        <p class="text-[10px] text-gray-500 mt-0.5">Bayar ke mekanik</p>
                                    @endif
                                    
                                    <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between items-center">
                                        <span class="text-xs font-bold text-gray-600">Total</span>
                                        <span class="font-bold text-gray-900">Rp {{ number_format($order->total_harga) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-xl border border-gray-200">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Bengkel Tujuan</h3>

                            <div class="flex items-center gap-3 mb-3">
                                <div class="h-10 w-10 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500 font-bold border border-gray-200 shadow-sm shrink-0">
                                    <i class="fa-solid fa-store"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-gray-800 leading-tight">{{ $order->tambalBan->nama_bengkel }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Owner: {{ $order->tambalBan->owner->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 leading-relaxed mb-3">
                                {{ Str::limit($order->tambalBan->alamat, 60) }}
                            </p>

                            <a href="https://wa.me/{{ $order->tambalBan->nomer_telepon }}" target="_blank" class="block w-full bg-white hover:bg-gray-100 text-gray-600 text-xs font-bold py-2.5 rounded-lg border border-gray-300 text-center transition">
                                <i class="fa-brands fa-whatsapp"></i> Hubungi Owner
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    @if($order->latitude && $order->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var lat = {{ $order->latitude }};
            var lng = {{ $order->longitude }};
            var map = L.map('mapAdminDetail', {zoomControl: false}).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

            var iconHtml = `<div class="relative flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-red-600 border border-white shadow-sm"></span></div>`;
            var icon = L.divIcon({className: 'bg-transparent border-none', html: iconHtml});

            L.marker([lat, lng], {icon: icon}).addTo(map).bindPopup("<b>Posisi Pelanggan</b>").openPopup();
        });
    </script>
    @endif
</x-app-layout>