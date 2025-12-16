<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">Form Pemesanan Jasa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">

                <div class="mb-6 border-b pb-4">
                    <p class="text-sm text-gray-500">Memesan jasa dari:</p>
                    <h3 class="text-lg font-bold text-blue-600">{{ $bengkel->nama_bengkel }}</h3>
                </div>

                <form action="{{ route('booking.store') }}" method="POST" class="space-y-5" id="bookingForm">
                    @csrf
                    <input type="hidden" name="tambal_ban_id" value="{{ $bengkel->id }}">

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titik Lokasi Anda (Wajib)</label>
                        <div id="mapPicker" class="w-full h-48 rounded-lg border border-gray-300 z-0"></div>
                        <button type="button" onclick="getLocation()"
                            class="mt-2 text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg border border-blue-200 hover:bg-blue-100 font-bold flex items-center gap-1 w-fit">
                            <i class="fa-solid fa-location-crosshairs"></i> Ambil Lokasi Saya Saat Ini
                        </button>
                        <p class="text-xs text-gray-400 mt-1">*Geser pin jika lokasi kurang akurat.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan</label>
                            <input type="text" name="nama_pemesan" value="{{ Auth::user()->name }}"
                                class="w-full rounded-lg border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                            <input type="number" name="nomer_telepon" class="w-full rounded-lg border-gray-300"
                                placeholder="08..." required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Detail Alamat / Patokan</label>
                        <textarea name="alamat_lengkap" rows="2" class="w-full rounded-lg border-gray-300"
                            placeholder="Contoh: Depan pagar hitam, sebelah warung..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kendaraan</label>
                        <div class="flex gap-4">
                            <label
                                class="flex items-center gap-2 border p-3 rounded-lg cursor-pointer w-full hover:bg-gray-50">
                                <input type="radio" name="jenis_kendaraan" value="motor" class="text-blue-600"
                                    checked>
                                <span><i class="fa-solid fa-motorcycle"></i> Motor</span>
                            </label>
                            <label
                                class="flex items-center gap-2 border p-3 rounded-lg cursor-pointer w-full hover:bg-gray-50">
                                <input type="radio" name="jenis_kendaraan" value="mobil" class="text-blue-600">
                                <span><i class="fa-solid fa-car"></i> Mobil</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan</label>
                        <input type="text" name="keluhan" class="w-full rounded-lg border-gray-300"
                            placeholder="Contoh: Bocor halus">
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <label
                                class="relative flex items-center justify-between p-4 bg-white border-2 border-blue-600 rounded-xl cursor-pointer shadow-sm transition hover:bg-blue-50">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="cod"
                                        class="w-5 h-5 text-blue-600 focus:ring-blue-500" checked>
                                    <div>
                                        <span class="block text-sm font-bold text-gray-800">Tunai (COD)</span>
                                        <span class="block text-xs text-gray-500">Bayar ke mekanik langsung</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-money-bill-wave text-blue-600 text-xl"></i>
                            </label>

                            <label
                                class="relative flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-xl opacity-60 cursor-not-allowed">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="transfer"
                                        class="w-5 h-5 text-gray-400" disabled>
                                    <div>
                                        <span class="block text-sm font-bold text-gray-500">Transfer / E-Wallet</span>
                                        <span
                                            class="block text-[10px] font-bold bg-gray-200 text-gray-600 px-2 py-0.5 rounded w-fit mt-1">SEGERA
                                            HADIR</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-credit-card text-gray-400 text-xl"></i>
                            </label>

                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 mt-6">
                        <button type="submit" id="submitBtn"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition transform active:scale-95 flex justify-center items-center gap-2">
                            <span>Buat Pesanan</span> <i class="fa-solid fa-arrow-right"></i>
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-3 flex items-center justify-center gap-1">
                            <i class="fa-solid fa-shield-halved"></i> Data Anda aman & terenkripsi.
                        </p>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('mapPicker').setView([-7.4478, 112.7183], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker;

        // Fungsi Deteksi Lokasi
        function getLocation() {
            if (!navigator.geolocation) {
                alert("Browser tidak support GPS");
                return;
            }

            navigator.geolocation.getCurrentPosition((pos) => {
                var lat = pos.coords.latitude;
                var lng = pos.coords.longitude;
                updatePosition(lat, lng);
                map.setView([lat, lng], 17);
            }, () => {
                alert("Gagal mengambil lokasi. Pastikan GPS aktif.");
            });
        }

        // Update Marker & Input
        function updatePosition(lat, lng) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (marker) marker.setLatLng([lat, lng]);
            else {
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);
                // Update saat marker digeser
                marker.on('dragend', function(e) {
                    var pos = marker.getLatLng();
                    document.getElementById('latitude').value = pos.lat;
                    document.getElementById('longitude').value = pos.lng;
                });
            }
        }

        // Panggil otomatis saat buka halaman
        getLocation();

        // Validasi sebelum submit (Wajib ada lokasi)
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            if (!document.getElementById('latitude').value) {
                e.preventDefault();
                alert("Mohon klik tombol 'Ambil Lokasi Saya' atau klik pada peta untuk menentukan lokasi.");
            }
        });

        // Klik peta manual
        map.on('click', function(e) {
            updatePosition(e.latlng.lat, e.latlng.lng);
        });
    </script>
</x-app-layout>
