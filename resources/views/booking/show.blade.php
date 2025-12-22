<x-app-layout>
    <head>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    </head>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('booking.history') }}" class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                Detail Pesanan #{{ $order->kode_order ?? $order->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">

                @php
                    $statusStyles = [
                        'pending' => ['bg' => 'bg-yellow-500', 'icon' => 'fa-clock', 'label' => 'Menunggu Konfirmasi'],
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
                            <p class="text-xs font-bold uppercase opacity-80 mb-1">Status Pesanan</p>
                            <h3 class="text-xl md:text-2xl font-bold">{{ $st['label'] }}</h3>
                        </div>
                    </div>
                    <div class="w-full md:w-auto text-left md:text-right bg-white/10 px-4 py-2 rounded-lg">
                        <p class="text-xs opacity-75 uppercase font-bold">Waktu Order</p>
                        <p class="font-mono text-base md:text-lg">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                @if($order->status == 'batal' && $order->alasan_batal)
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 m-4 md:m-6 mb-0 rounded-r-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5"><i class="fa-solid fa-circle-info text-red-500 text-lg"></i></div>
                            <div>
                                <h3 class="text-sm font-bold text-red-800">Pesanan Dibatalkan</h3>
                                <div class="mt-1 text-sm text-red-700">Alasan: <span class="font-bold">"{{ $order->alasan_batal }}"</span></div>
                                <div class="mt-2">
                                    <a href="{{ route('peta.index') }}" class="text-xs font-bold text-red-600 hover:text-red-800 underline">Cari Bengkel Lain →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="p-4 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-10">

                    <div class="space-y-6">
                        
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Penyedia Jasa</h4>
                            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl text-blue-600 border border-blue-100 shrink-0">
                                        <i class="fa-solid fa-store"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $order->tambalBan->nama_bengkel }}</h4>
                                        <p class="text-sm text-gray-500 leading-relaxed mt-1">{{ $order->tambalBan->alamat }}</p>
                                        
                                        <div class="mt-4 grid grid-cols-2 gap-2">
                                            @php
                                                $wa = $order->tambalBan->nomer_telepon;
                                                if (substr($wa, 0, 1) == '0') $wa = '62' . substr($wa, 1);
                                            @endphp
                                            
                                            <a href="https://wa.me/{{ $wa }}" target="_blank" class="bg-green-50 text-green-600 border border-green-200 text-xs font-bold py-2 rounded-lg hover:bg-green-100 transition flex items-center justify-center gap-1 text-center">
                                                <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                                            </a>

                                            @if($order->status == 'proses')
                                                <a href="{{ route('chat.show', $order->id) }}" class="bg-blue-600 text-white text-xs font-bold py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-md animate-pulse-soft text-center">
                                                    <i class="fa-regular fa-comments text-sm"></i> Live Chat
                                                </a>
                                            @else
                                                <button disabled class="bg-gray-100 text-gray-400 border border-gray-200 text-xs font-bold py-2 rounded-lg cursor-not-allowed flex items-center justify-center gap-1">
                                                    <i class="fa-regular fa-comments"></i> Chat (Off)
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 relative overflow-hidden">
                                <p class="text-xs text-gray-500 font-bold uppercase mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-wallet"></i> Rincian Pembayaran
                                </p>

                                @if($order->metode_pembayaran == 'transfer')
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-sm font-bold text-blue-700">Transfer / E-Wallet</span>
                                        <i class="fa-solid fa-credit-card text-blue-200 text-2xl"></i>
                                    </div>
                                    
                                    <div class="border-t border-gray-200 pt-3">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-sm text-gray-600">Total Tagihan</span>
                                            <span class="text-xl font-bold text-gray-900">Rp {{ number_format($order->total_harga) }}</span>
                                        </div>

                                        @if($order->status == 'batal')
                                            <div class="bg-gray-200 text-gray-500 p-3 rounded-lg font-bold text-center border border-gray-300 text-xs">
                                                <i class="fa-solid fa-ban mr-1"></i> TRANSAKSI DIBATALKAN
                                            </div>
                                        @elseif($order->payment_status == 'paid')
                                            <div class="bg-green-100 text-green-700 p-3 rounded-lg font-bold text-center border border-green-300 text-xs flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-circle-check"></i> LUNAS
                                            </div>
                                        @elseif($order->payment_status == 'unpaid')
                                            <button id="pay-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg transition flex items-center justify-center gap-2 text-sm transform active:scale-95">
                                                Bayar Sekarang <i class="fa-solid fa-angle-right"></i>
                                            </button>
                                            <p class="text-[10px] text-center text-gray-500 mt-2">Klik tombol untuk menyelesaikan pembayaran.</p>
                                        @else
                                            <div class="bg-red-100 text-red-700 p-2 rounded text-xs text-center font-bold">Gagal / Kadaluarsa</div>
                                        @endif
                                    </div>

                                @else
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-sm font-bold text-gray-800">Tunai (COD)</span>
                                        <i class="fa-solid fa-money-bill-wave text-green-200 text-2xl"></i>
                                    </div>
                                    <div class="bg-white p-3 rounded border border-gray-200">
                                        <p class="text-sm text-gray-500 text-center">
                                            Siapkan uang tunai <span class="font-bold text-gray-900">Rp {{ number_format($order->total_harga) }}</span> saat mekanik tiba.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="space-y-6">
                        
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Rincian Order</h4>
                            
                            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold">Nama Pemesan</p>
                                        <p class="font-bold text-gray-800 text-sm">{{ $order->nama_pemesan }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold">Kendaraan</p>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 rounded text-xs font-bold text-gray-700 capitalize mt-0.5 border border-gray-200">
                                            <i class="fa-solid {{ $order->jenis_kendaraan == 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i> {{ $order->jenis_kendaraan }}
                                        </span>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-gray-300 mb-4">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Keluhan</p>
                                    <p class="text-gray-700 text-sm italic">"{{ $order->keluhan }}"</p>
                                </div>

                                @if($order->foto_ban)
                                    <div class="mb-4">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-2">Foto Kondisi Ban</p>
                                        <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm relative group h-32 w-full bg-gray-100">
                                            <img src="{{ asset('storage/' . $order->foto_ban) }}" alt="Kondisi Ban" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src, '_blank')">
                                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                <span class="bg-black/50 text-white text-[10px] px-2 py-1 rounded backdrop-blur-sm"><i class="fa-solid fa-magnifying-glass"></i> Zoom</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Lokasi Penjemputan</p>
                                    <p class="text-sm text-gray-800 flex gap-2 leading-snug">
                                        <i class="fa-solid fa-map-pin text-red-500 mt-0.5 shrink-0"></i> 
                                        {{ $order->alamat_lengkap }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($order->latitude && $order->longitude)
                            <div class="rounded-xl overflow-hidden border border-gray-200 shadow-md relative group h-56 w-full">
                                <div id="mapUserLocation" class="w-full h-full z-0 bg-gray-100"></div>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="absolute bottom-3 right-3 bg-white text-blue-600 text-xs font-bold px-3 py-2 rounded-lg shadow-lg hover:bg-gray-50 transition flex items-center gap-1 z-[400]">
                                    <i class="fa-solid fa-map-location-dot"></i> Buka di Google Maps
                                </a>
                            </div>
                        @endif

                    </div>
                </div>

                @if($order->status == 'pending')
                    <div class="bg-gray-50 p-4 md:p-6 border-t border-gray-100 text-center">
                        <form id="cancelForm" action="{{ route('booking.cancel', $order->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="button" onclick="confirmCancel()" class="text-red-500 hover:text-red-700 text-sm font-bold hover:underline transition flex items-center justify-center gap-1 mx-auto bg-white px-4 py-2 rounded-lg border border-red-100 shadow-sm hover:shadow">
                                <i class="fa-solid fa-ban"></i> Batalkan Pesanan Ini
                            </button>
                        </form>
                        <p class="text-[10px] text-gray-400 mt-2">*Hanya bisa dibatalkan sebelum bengkel mengonfirmasi.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. Logic Midtrans
        var payButton = document.getElementById('pay-button');
        if (payButton) {
            payButton.addEventListener('click', function () {
                @if($order->snap_token)
                    window.snap.pay('{{ $order->snap_token }}', {
                        onSuccess: function (result) { Swal.fire("Berhasil!", "Pembayaran sukses!", "success").then(() => window.location.reload()); },
                        onPending: function (result) { Swal.fire("Menunggu!", "Selesaikan pembayaran Anda.", "info").then(() => window.location.reload()); },
                        onError: function (result) { Swal.fire("Gagal!", "Pembayaran gagal.", "error").then(() => window.location.reload()); }
                    });
                @else
                    Swal.fire("Error", "Token pembayaran hilang.", "error");
                @endif
            });
        }

        // 2. Logic Batal
        function confirmCancel() {
            Swal.fire({
                title: 'Batalkan?', text: "Pesanan akan dihapus.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Batalkan'
            }).then((result) => { if (result.isConfirmed) document.getElementById('cancelForm').submit(); });
        }

        // 3. Logic Peta (Diperbaiki Z-Index)
        @if($order->latitude && $order->longitude)
            document.addEventListener("DOMContentLoaded", function () {
                var lat = {{ $order->latitude }}, lng = {{ $order->longitude }};
                var map = L.map('mapUserLocation', { zoomControl: false }).setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
                
                var iconHtml = `<div class="relative flex h-4 w-4"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 border-2 border-white shadow-md"></span></div>`;
                var icon = L.divIcon({ className: 'bg-transparent border-none', html: iconHtml });
                L.marker([lat, lng], { icon: icon }).addTo(map);
            });
        @endif
    </script>
</x-app-layout>