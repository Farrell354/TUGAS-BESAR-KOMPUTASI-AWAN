<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-bold text-xl md:text-2xl text-gray-900 leading-tight">Tambah Data Baru</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <form action="{{ route('tambal-ban.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

                    <div class="lg:col-span-1 space-y-6 order-2 lg:order-1">
                        
                        <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                                <i class="fa-solid fa-store text-blue-600"></i> Informasi Bengkel
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bengkel</label>
                                    <input type="text" name="nama_bengkel" value="{{ old('nama_bengkel') }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Contoh: Bengkel Barokah" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tetapkan Owner</label>
                                    <select name="user_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 text-sm">
                                        <option value="">-- Pilih Akun Owner --</option>
                                        @foreach ($owners as $owner)
                                            <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-gray-500 mt-1">Akun ini akan memiliki akses dashboard mitra.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bengkel</label>
                                    <input type="file" name="gambar" class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                        <select name="kategori" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500">
                                            <option value="motor">Motor</option>
                                            <option value="mobil">Mobil</option>
                                            <option value="keduanya">Keduanya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Buka - Tutup</label>
                                        <div class="flex items-center gap-1">
                                            <input type="time" name="jam_buka" class="w-full rounded-lg border-gray-300 text-xs px-1 text-center" required>
                                            <span class="text-gray-400">-</span>
                                            <input type="time" name="jam_tutup" class="w-full rounded-lg border-gray-300 text-xs px-1 text-center" required>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm font-bold">+62</span>
                                        <input type="number" name="nomer_telepon" value="{{ old('nomer_telepon') }}" class="w-full pl-12 rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm" placeholder="81234567890" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                    <textarea name="alamat" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 text-sm" placeholder="Nama jalan, RT/RW, Patokan...">{{ old('alamat') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                                <i class="fa-solid fa-map-pin text-red-600"></i> Koordinat Lokasi
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500 font-bold mb-1 block">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" placeholder="-7.xxxx" class="w-full rounded-lg border-gray-300 text-sm bg-white focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 font-bold mb-1 block">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" placeholder="112.xxxx" class="w-full rounded-lg border-gray-300 text-sm bg-white focus:ring-blue-500" required>
                                </div>
                            </div>
                            <p class="text-xs text-blue-600 mt-2 bg-blue-50 p-2 rounded border border-blue-100 flex items-start gap-1">
                                <i class="fa-solid fa-circle-info mt-0.5"></i>
                                Geser peta atau ketik koordinat secara manual.
                            </p>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-save"></i> Simpan Data Bengkel
                        </button>
                    </div>

                    <div class="lg:col-span-2 h-[400px] md:h-auto md:min-h-[600px] order-1 lg:order-2 sticky top-4">
                        <div class="bg-white p-2 rounded-xl shadow-md border border-gray-200 h-full flex flex-col relative overflow-hidden">
                            
                            <div class="absolute top-4 left-4 z-[400] bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow-md border border-gray-300 text-xs font-bold text-gray-700 flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-red-500 animate-pulse"></span> Mode Input Lokasi
                            </div>

                            <div id="mapInput" class="flex-1 rounded-lg z-0 w-full h-full border border-gray-300"></div>
                            
                            <div class="mt-2 text-center md:hidden">
                                <p class="text-xs text-gray-400">Gunakan dua jari untuk zoom peta di HP.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Init Map
        var map = L.map('mapInput', { zoomControl: false }).setView([-7.4478, 112.7183], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        var marker;

        // Custom Icon
        var iconEdit = L.divIcon({
            className: 'bg-transparent',
            html: `<div class="relative"><div class="h-4 w-4 bg-red-600 rounded-full border-2 border-white shadow-md animate-bounce"></div></div>`,
            iconSize: [20, 20], iconAnchor: [10, 10]
        });

        // Fungsi Update Marker saat Input Manual
        function updateMarkerFromInput() {
            var lat = parseFloat(document.getElementById('latitude').value);
            var lng = parseFloat(document.getElementById('longitude').value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                var newLatLng = new L.LatLng(lat, lng);
                
                if (marker) {
                    marker.setLatLng(newLatLng);
                } else {
                    marker = L.marker(newLatLng, { draggable: true }).addTo(map);
                    // Tambahkan listener dragend untuk marker baru
                    marker.on('dragend', function(ev) {
                        var pos = ev.target.getLatLng();
                        document.getElementById('latitude').value = pos.lat.toFixed(6);
                        document.getElementById('longitude').value = pos.lng.toFixed(6);
                    });
                }
                map.panTo(newLatLng);
            }
        }

        // Listener saat mengetik di input
        document.getElementById('latitude').addEventListener('input', updateMarkerFromInput);
        document.getElementById('longitude').addEventListener('input', updateMarkerFromInput);

        // Event Klik Peta
        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (marker) marker.setLatLng(e.latlng);
            else marker = L.marker(e.latlng, { draggable: true }).addTo(map);

            // Jika marker digeser manual
            marker.on('dragend', function(ev) {
                var pos = ev.target.getLatLng();
                document.getElementById('latitude').value = pos.lat.toFixed(6);
                document.getElementById('longitude').value = pos.lng.toFixed(6);
            });
        });

        // Tombol Current Location
        var btnLoc = L.control({position: 'topright'});
        btnLoc.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
            div.innerHTML = '<button type="button" class="bg-white p-2 rounded shadow text-gray-600 hover:text-blue-600 w-8 h-8 flex items-center justify-center border border-gray-300" title="Lokasi Saya"><i class="fa-solid fa-crosshairs"></i></button>';
            div.onclick = function(e) {
                e.preventDefault(); 
                navigator.geolocation.getCurrentPosition(pos => {
                    var lat = pos.coords.latitude; var lng = pos.coords.longitude;
                    map.flyTo([lat, lng], 16);
                    // Opsional: Langsung set marker di lokasi user
                    // document.getElementById('latitude').value = lat;
                    // document.getElementById('longitude').value = lng;
                    // if(marker) marker.setLatLng([lat, lng]); else marker = L.marker([lat, lng], {draggable:true}).addTo(map);
                });
            };
            return div;
        };
        btnLoc.addTo(map);
    </script>
</x-app-layout>