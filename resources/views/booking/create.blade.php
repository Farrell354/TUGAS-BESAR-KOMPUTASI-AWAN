<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">Form Pemesanan Jasa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">

                <div class="mb-6 border-b pb-4 flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">Memesan jasa dari:</p>
                        <h3 class="text-lg font-bold text-blue-600">{{ $bengkel->nama_bengkel }}</h3>
                        <p class="text-xs text-gray-400 mt-1"><i class="fa-solid fa-location-dot"></i> {{ $bengkel->alamat }}</p>
                    </div>
                    <div class="text-right">
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border border-blue-400">
                            Buka: {{ \Carbon\Carbon::parse($bengkel->jam_buka)->format('H:i') }} - {{ \Carbon\Carbon::parse($bengkel->jam_tutup)->format('H:i') }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('booking.store') }}" method="POST" class="space-y-5" id="bookingForm">
                    @csrf
                    <input type="hidden" name="tambal_ban_id" value="{{ $bengkel->id }}">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-2">1. Tentukan Lokasi Anda (Wajib)</label>
                        
                        <div id="mapPicker" class="w-full h-56 rounded-lg border border-gray-300 z-0 relative"></div>
                        
                        <div class="flex justify-between items-center mt-3">
                            <button type="button" onclick="getLocation()"
                                class="text-xs bg-white text-blue-600 px-3 py-2 rounded-lg border border-blue-200 hover:bg-blue-50 font-bold flex items-center gap-1 shadow-sm transition">
                                <i class="fa-solid fa-location-crosshairs"></i> Ambil Lokasi Saya
                            </button>
                            <p class="text-[10px] text-gray-400">*Geser pin biru ke lokasi tepat Anda.</p>
                        </div>

                        <div id="priceInfo" class="hidden mt-4 bg-white border border-blue-100 rounded-lg p-3 shadow-sm animate-fade-in-up">
                            <div class="flex justify-between items-center divide-x divide-gray-100">
                                <div class="pr-4 w-1/2">
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wide">Jarak ke Bengkel</p>
                                    <p class="text-lg font-bold text-gray-800" id="distanceDisplay">0 km</p>
                                </div>
                                <div class="pl-4 w-1/2 text-right">
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wide">Estimasi Biaya</p>
                                    <p class="text-xl font-bold text-blue-600" id="priceDisplay">Rp 0</p>
                                </div>
                            </div>
                            <div id="distanceWarning" class="hidden mt-2 text-xs text-red-600 font-bold bg-red-50 p-2 rounded border border-red-100 flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                                <span>Maaf, lokasi terlalu jauh (>10km). Layanan tidak tersedia.</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">2. Lengkapi Data Pesanan</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nama Pemesan</label>
                                <input type="text" name="nama_pemesan" value="{{ Auth::user()->name }}"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">No. WhatsApp</label>
                                <input type="number" name="nomer_telepon" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="08..." required>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Detail Alamat / Patokan</label>
                        <textarea name="alamat_lengkap" rows="2" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Contoh: Depan pagar hitam, sebelah warung..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-2">Jenis Kendaraan</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 border p-3 rounded-lg cursor-pointer w-full hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="jenis_kendaraan" value="motor" class="text-blue-600 focus:ring-blue-500" checked>
                                <span><i class="fa-solid fa-motorcycle text-gray-600 mr-1"></i> Motor</span>
                            </label>
                            <label class="flex items-center gap-2 border p-3 rounded-lg cursor-pointer w-full hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="jenis_kendaraan" value="mobil" class="text-blue-600 focus:ring-blue-500">
                                <span><i class="fa-solid fa-car text-gray-600 mr-1"></i> Mobil</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Keluhan</label>
                        <input type="text" name="keluhan" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Contoh: Bocor halus, ban robek">
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-3">3. Pilih Metode Pembayaran</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <label class="relative flex items-center justify-between p-4 bg-white border-2 border-gray-200 rounded-xl cursor-pointer shadow-sm transition hover:border-blue-300 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="cod" class="w-5 h-5 text-blue-600 focus:ring-blue-500" checked>
                                    <div>
                                        <span class="block text-sm font-bold text-gray-800">Tunai (COD)</span>
                                        <span class="block text-[10px] text-gray-500">Bayar tunai ke mekanik</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-money-bill-wave text-green-600 text-xl"></i>
                            </label>

                            <label class="relative flex items-center justify-between p-4 bg-white border-2 border-gray-200 rounded-xl cursor-pointer shadow-sm transition hover:border-blue-300 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="transfer" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <span class="block text-sm font-bold text-gray-800">Transfer / E-Wallet</span>
                                        <span class="block text-[10px] text-gray-500">QRIS, Gopay, VA Bank</span>
                                    </div>
                                </div>
                                <i class="fa-regular fa-credit-card text-blue-600 text-xl"></i>
                            </label>

                        </div>
                    </div>

                    <div class="pt-6 mt-2">
                        <button type="submit" id="submitBtn" disabled
                            class="w-full bg-gray-400 cursor-not-allowed text-white font-bold py-4 rounded-xl shadow-lg transition transform active:scale-95 flex justify-center items-center gap-2">
                            <span>Tentukan Lokasi Dulu...</span>
                        </button>
                        <p class="text-center text-[10px] text-gray-400 mt-3 flex items-center justify-center gap-1">
                            <i class="fa-solid fa-shield-halved"></i> Transaksi Aman & Terpantau Sistem.
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
<script>
        // DATA BENGKEL & HARGA (DARI DATABASE)
        var bengkelLat = {{ $bengkel->latitude }};
        var bengkelLng = {{ $bengkel->longitude }};
        
        // Simpan harga dalam Object JS agar mudah diakses
        var rates = {
            motor: {
                dekat: {{ $bengkel->harga_motor_dekat }}, // misal: 20000
                jauh: {{ $bengkel->harga_motor_jauh }}    // misal: 35000
            },
            mobil: {
                dekat: {{ $bengkel->harga_mobil_dekat }}, // misal: 35000
                jauh: {{ $bengkel->harga_mobil_jauh }}    // misal: 50000
            }
        };

        // Init Peta
        var map = L.map('mapPicker').setView([bengkelLat, bengkelLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

        var shopIcon = L.divIcon({className: 'bg-transparent', html: '<i class="fa-solid fa-store text-3xl text-red-600 drop-shadow-md"></i>', iconSize: [30, 30], iconAnchor: [15, 30]});
        L.marker([bengkelLat, bengkelLng], {icon: shopIcon}).addTo(map).bindPopup("<b>{{ $bengkel->nama_bengkel }}</b>").openPopup();

        var userMarker;
        var currentLat = 0;
        var currentLng = 0;

        // --- RUMUS JARAK ---
        function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
            var R = 6371; 
            var dLat = deg2rad(lat2 - lat1);
            var dLon = deg2rad(lon2 - lon1);
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }
        function deg2rad(deg) { return deg * (Math.PI / 180); }

        // --- FUNGSI UTAMA HITUNG HARGA ---
        function updatePriceUI() {
            if(currentLat == 0 || currentLng == 0) return;

            var dist = getDistanceFromLatLonInKm(currentLat, currentLng, bengkelLat, bengkelLng);
            var price = 0;
            var vehicleType = document.querySelector('input[name="jenis_kendaraan"]:checked').value;

            document.getElementById('priceInfo').classList.remove('hidden');
            document.getElementById('distanceDisplay').innerText = dist.toFixed(1) + " km";
            
            var submitBtn = document.getElementById('submitBtn');
            var warningBox = document.getElementById('distanceWarning');
            var priceDisplay = document.getElementById('priceDisplay');

            if (dist > 10) {
                // KASUS: KEJAUHAN
                priceDisplay.innerText = "-";
                warningBox.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.className = "w-full bg-red-100 text-red-400 font-bold py-4 rounded-xl cursor-not-allowed flex justify-center items-center gap-2";
                submitBtn.innerHTML = '<i class="fa-solid fa-ban"></i> Jarak Terlalu Jauh';
            } else {
                // KASUS: AMAN
                warningBox.classList.add('hidden');
                
                // --- AMBIL HARGA DARI VARIABEL 'rates' DI ATAS ---
                if(vehicleType === 'mobil') {
                    if (dist <= 5) price = rates.mobil.dekat;
                    else price = rates.mobil.jauh;
                } else {
                    if (dist <= 5) price = rates.motor.dekat;
                    else price = rates.motor.jauh;
                }
                // ------------------------------------------------

                priceDisplay.innerText = "Rp " + price.toLocaleString('id-ID');
                
                submitBtn.disabled = false;
                submitBtn.className = "w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg transition transform active:scale-95 flex justify-center items-center gap-2";
                submitBtn.innerHTML = '<span>Buat Pesanan</span> <i class="fa-solid fa-arrow-right"></i>';
            }
        }

        // --- UPDATE POSISI ---
        function updatePosition(lat, lng) {
            currentLat = lat;
            currentLng = lng;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            
            updatePriceUI(); 

            if (userMarker) {
                userMarker.setLatLng([lat, lng]);
            } else {
                userMarker = L.marker([lat, lng], { draggable: true }).addTo(map);
                userMarker.on('dragend', function(e) {
                    var pos = userMarker.getLatLng();
                    updatePosition(pos.lat, pos.lng);
                });
            }
        }

        function getLocation() {
            if (!navigator.geolocation) { alert("Browser error"); return; }
            var btn = document.querySelector('button[onclick="getLocation()"]');
            var oldText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';

            navigator.geolocation.getCurrentPosition((pos) => {
                updatePosition(pos.coords.latitude, pos.coords.longitude);
                map.setView([pos.coords.latitude, pos.coords.longitude], 15);
                btn.innerHTML = oldText;
            }, () => { alert("GPS Error"); btn.innerHTML = oldText; });
        }

        map.on('click', function(e) { updatePosition(e.latlng.lat, e.latlng.lng); });

        var radios = document.querySelectorAll('input[name="jenis_kendaraan"]');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                updatePriceUI(); 
            });
        });

        getLocation();
    </script>
</x-app-layout>