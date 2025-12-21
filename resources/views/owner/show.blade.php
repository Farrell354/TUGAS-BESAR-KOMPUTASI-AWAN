<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.dashboard') }}" class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-bold text-xl text-gray-800">Detail Pesanan #{{ $order->kode_order ?? $order->id }}</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">

                <div class="p-6 bg-gray-50 border-b border-gray-100 flex flex-col md:flex-row justify-between md:items-center gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Status Pesanan</p>
                        @php
                            $colors = [
                                'pending' => 'text-yellow-600 bg-yellow-100 border-yellow-200',
                                'proses' => 'text-blue-600 bg-blue-100 border-blue-200',
                                'selesai' => 'text-green-600 bg-green-100 border-green-200',
                                'batal' => 'text-red-600 bg-red-100 border-red-200'
                            ];
                            $labels = [
                                'pending' => 'Menunggu Konfirmasi',
                                'proses' => 'Sedang Dikerjakan',
                                'selesai' => 'Selesai',
                                'batal' => 'Dibatalkan'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-lg font-bold text-sm uppercase border {{ $colors[$order->status] ?? 'text-gray-600 bg-gray-100' }}">
                            <i class="fa-solid fa-circle text-[10px] mr-1"></i> {{ $labels[$order->status] ?? $order->status }}
                        </span>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-xs text-gray-400 font-bold uppercase">Waktu Pemesanan</p>
                        <p class="text-sm font-bold text-gray-700">{{ $order->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>

                <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-10">

                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Informasi Pelanggan</h4>

                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-14 w-14 bg-blue-50 rounded-full flex items-center justify-center text-2xl text-blue-600 shadow-sm border border-blue-100">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-lg">{{ $order->nama_pemesan }}</p>
                                <a href="https://wa.me/{{ $order->nomer_telepon }}" target="_blank" class="text-green-600 hover:text-green-700 font-medium text-sm flex items-center gap-1 transition">
                                    <i class="fa-brands fa-whatsapp"></i> {{ $order->nomer_telepon }}
                                </a>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 mb-6">
                            <p class="text-xs text-blue-600 font-bold uppercase mb-2 flex items-center gap-1">
                                <i class="fa-solid fa-map-location-dot"></i> Lokasi Kejadian
                            </p>
                            <p class="text-gray-800 text-sm leading-relaxed">{{ $order->alamat_lengkap }}</p>
                        </div>

                        @if($order->latitude && $order->longitude)
                            <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm relative group">
                                <div id="mapCustomer" class="w-full h-56 z-0"></div>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="absolute bottom-3 right-3 bg-white/90 backdrop-blur text-blue-600 text-xs font-bold px-3 py-2 rounded-lg shadow-md hover:bg-blue-600 hover:text-white transition flex items-center gap-2">
                                    <i class="fa-solid fa-diamond-turn-right"></i> Navigasi Google Maps
                                </a>
                            </div>
                        @else
                            <div class="p-4 bg-yellow-50 text-yellow-700 text-sm rounded-lg border border-yellow-200 text-center">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> User tidak menyertakan koordinat GPS.
                            </div>
                        @endif
                    </div>

                    <div>

                        @if($order->metode_pembayaran == 'transfer')
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6 shadow-sm">
                                <div class="flex items-start gap-4">
                                    <div class="bg-blue-100 p-3 rounded-lg text-blue-600 shrink-0">
                                        <i class="fa-solid fa-credit-card text-2xl"></i>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-blue-700 font-bold uppercase tracking-wider mb-1">Metode Pembayaran</p>
                                        <h3 class="text-xl font-extrabold text-gray-800">TRANSFER / E-WALLET</h3>
                                        
                                        <div class="mt-2 flex justify-between items-center bg-white/50 p-2 rounded border border-blue-100">
                                            <span class="text-xs font-bold text-gray-500">Status Bayar:</span>
                                            @if($order->payment_status == 'paid')
                                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-bold border border-green-200 flex items-center gap-1">
                                                    <i class="fa-solid fa-check-circle"></i> LUNAS
                                                </span>
                                            @else
                                                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded font-bold border border-yellow-200 flex items-center gap-1">
                                                    <i class="fa-solid fa-hourglass"></i> BELUM DIBAYAR
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-blue-600 mt-2 font-bold">Total: Rp {{ number_format($order->total_harga) }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-6 shadow-sm">
                                <div class="flex items-start gap-4">
                                    <div class="bg-green-100 p-3 rounded-lg text-green-600 shrink-0">
                                        <i class="fa-solid fa-wallet text-2xl"></i>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-green-700 font-bold uppercase tracking-wider mb-1">Metode Pembayaran</p>
                                        <h3 class="text-xl font-extrabold text-gray-800">TUNAI (COD)</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Silakan tagih sebesar <strong>Rp {{ number_format($order->total_harga) }}</strong> ke pelanggan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Detail Kendaraan</h4>

                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 font-bold text-sm border border-gray-200">
                                <i class="fa-solid {{ $order->jenis_kendaraan == 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i>
                                {{ ucfirst($order->jenis_kendaraan) }}
                            </span>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-gray-300 mb-8">
                            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Keluhan Pelanggan</p>
                            <p class="text-gray-700 text-sm italic">"{{ $order->keluhan }}"</p>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tindakan Anda</h4>

                            @if($order->status == 'pending')
                                <div class="grid grid-cols-2 gap-3">
                                    <form action="{{ route('owner.order.update', $order->id) }}" method="POST">
                                        @csrf
                                        <button name="action" value="accept" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-200 transition transform active:scale-95 flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-check"></i> TERIMA
                                        </button>
                                    </form>

                                    <button type="button" onclick="rejectOrder()" class="w-full bg-white hover:bg-red-50 text-red-600 border border-red-200 py-3.5 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-xmark"></i> TOLAK
                                    </button>
                                </div>

                                <form id="rejectForm" action="{{ route('owner.order.update', $order->id) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="alasan" id="inputAlasan">
                                </form>

                            @elseif($order->status == 'proses')
                                <div class="grid grid-cols-1 gap-3">
                                    <a href="{{ route('chat.show', $order->id) }}" class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-blue-200 transition">
                                        <i class="fa-regular fa-comments text-lg"></i> Chat Pelanggan
                                    </a>

                                    <form id="finishForm" action="{{ route('owner.order.update', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="button" onclick="confirmFinish()" class="w-full bg-green-500 hover:bg-green-600 text-white py-3.5 rounded-xl font-bold shadow-md transition flex items-center justify-center gap-2 border-b-4 border-green-700 active:border-b-0 active:translate-y-1">
                                            <i class="fa-solid fa-flag-checkered text-lg"></i> Tandai Selesai
                                        </button>
                                    </form>
                                </div>

                            @else
                                <div class="text-center p-4 bg-gray-100 rounded-xl text-gray-500 text-sm border border-gray-200">
                                    <i class="fa-solid fa-lock mr-1"></i> Pesanan ini telah selesai atau dibatalkan.
                                    @if($order->status == 'batal' && $order->alasan_batal)
                                        <p class="text-xs text-red-500 mt-1">Alasan: {{ $order->alasan_batal }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. Script Tolak Pesanan
        function rejectOrder() {
            Swal.fire({
                title: 'Tolak Pesanan?',
                text: "Berikan alasan kepada pelanggan:",
                icon: 'warning',
                input: 'select',
                inputOptions: {
                    'Mekanik Sedang Sibuk / Penuh': 'Mekanik Sedang Sibuk / Penuh',
                    'Jarak Terlalu Jauh': 'Jarak Terlalu Jauh',
                    'Stok Habis': 'Stok Habis',
                    'Bengkel Tutup': 'Bengkel Tutup',
                    'Lainnya': 'Lainnya'
                },
                inputPlaceholder: 'Pilih alasan...',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Tolak Pesanan',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Anda harus memilih alasan!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('inputAlasan').value = result.value;
                    document.getElementById('rejectForm').submit();
                }
            });
        }

        // 2. Script Selesai Pesanan
        function confirmFinish() {
            Swal.fire({
                title: 'Selesaikan Pesanan?',
                text: "Pastikan jasa sudah selesai dikerjakan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Selesai!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.getElementById('finishForm');
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'action';
                    input.value = 'finish';
                    form.appendChild(input);
                    form.submit();
                }
            });
        }

        // 3. Script Peta
        @if($order->latitude && $order->longitude)
        document.addEventListener("DOMContentLoaded", function() {
            var lat = {{ $order->latitude }};
            var lng = {{ $order->longitude }};
            var map = L.map('mapCustomer', {zoomControl: false}).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
            var iconHtml = `<div class="relative flex h-4 w-4"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 border-2 border-white shadow-md"></span></div>`;
            var icon = L.divIcon({className: 'bg-transparent border-none', html: iconHtml});
            L.marker([lat, lng], {icon: icon}).addTo(map).bindPopup("<b>Lokasi Pelanggan</b>").openPopup();
        });
        @endif
    </script>
</x-app-layout>