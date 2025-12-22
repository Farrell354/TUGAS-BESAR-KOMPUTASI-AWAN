<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-bold text-xl md:text-2xl text-gray-900 leading-tight truncate">Edit: {{ $tambalBan->nama_bengkel }}</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <form action="{{ route('tambal-ban.update', $tambalBan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

                    <div class="lg:col-span-1 space-y-6 order-2 lg:order-1">
                        
                        <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                                <i class="fa-solid fa-pen-to-square text-blue-600"></i> Informasi Bengkel
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bengkel</label>
                                    <input type="text" name="nama_bengkel" value="{{ old('nama_bengkel', $tambalBan->nama_bengkel) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
                                    <select name="user_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 text-sm">
                                        <option value="">-- Belum Ada Owner --</option>
                                        @foreach ($owners as $owner)
                                            <option value="{{ $owner->id }}" {{ $tambalBan->user_id == $owner->id ? 'selected' : '' }}>
                                                {{ $owner->name }} ({{ $owner->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bengkel</label>
                                    @if ($tambalBan->gambar)
                                        <div class="mb-2 relative group w-full h-32 rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ asset('storage/' . $tambalBan->gambar) }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <span class="text-white text-xs font-bold">Foto Saat Ini</span>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" name="gambar" class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-[10px] text-gray-400 mt-1">*Kosongkan jika tidak ingin mengubah foto.</p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                        <select name="kategori" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500">
                                            <option value="motor" {{ $tambalBan->kategori == 'motor' ? 'selected' : '' }}>Motor</option>
                                            <option value="mobil" {{ $tambalBan->kategori == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                            <option value="keduanya" {{ $tambalBan->kategori == 'keduanya' ? 'selected' : '' }}>Keduanya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                                        <div class="flex items-center gap-1">
                                            <input type="time" name="jam_buka" value="{{ $tambalBan->jam_buka }}" class="w-full rounded-lg border-gray-300 text-xs px-1 text-center">
                                            <span class="text-gray-400">-</span>
                                            <input type="time" name="jam_tutup" value="{{ $tambalBan->jam_tutup }}" class="w-full rounded-lg border-gray-300 text-xs px-1 text-center">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm font-bold">+62</span>
                                        <input type="number" name="nomer_telepon" value="{{ old('nomer_telepon', $tambalBan->nomer_telepon) }}" class="w-full pl-12 rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea name="alamat" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 text-sm">{{ old('alamat', $tambalBan->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                                <i class="fa-solid fa-map-pin text-red-600"></i> Koordinat
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500 font-bold mb-1 block">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" value="{{ $tambalBan->latitude }}" class="w-full rounded-lg border-gray-300 text-sm bg-gray-50" readonly required>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 font-bold mb-1 block">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" value="{{ $tambalBan->longitude }}" class="w-full rounded-lg border-gray-300 text-sm bg-gray-50" readonly required>
                                </div>
                            </div>
                            <p class="text-xs text-orange-600 mt-2 font-bold flex items-center gap-1 bg-orange-50 p-2 rounded border border-orange-100">
                                <i class="fa-solid fa-arrows-up-down-left-right"></i> Geser marker di peta untuk update.
                            </p>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>

                    <div class="lg:col-span-2 h-[400px] md:h-auto md:min-h-[600px] order-1 lg:order-2 sticky top-4">
                        <div class="bg-white p-2 rounded-xl shadow-md border border-gray-200 h-full flex flex-col relative overflow-hidden">
                            
                            <div class="absolute top-4 left-4 z-[400] bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow-md border border-gray-300 text-xs font-bold text-gray-700 flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-orange-500 animate-pulse"></span> Mode Edit Lokasi
                            </div>

                            <div id="mapEdit" class="flex-1 rounded-lg z-0 w-full h-full border border-gray-300"></div>
                            
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
        // Init Map dengan data existing
        var curLat = {{ $tambalBan->latitude }};
        var curLng = {{ $tambalBan->longitude }};
        
        var map = L.map('mapEdit', { zoomControl: false }).setView([curLat, curLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Marker Draggable
        var marker = L.marker([curLat, curLng], { draggable: true }).addTo(map);

        // Update Marker saat Input diketik manual
        function updateMarkerFromInput() {
            var lat = parseFloat(document.getElementById('latitude').value);
            var lng = parseFloat(document.getElementById('longitude').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                var newLatLng = new L.LatLng(lat, lng);
                marker.setLatLng(newLatLng);
                map.panTo(newLatLng);
            }
        }
        document.getElementById('latitude').addEventListener('input', updateMarkerFromInput);
        document.getElementById('longitude').addEventListener('input', updateMarkerFromInput);

        // Update Input saat Marker digeser
        marker.on('dragend', function(e) {
            var pos = marker.getLatLng();
            document.getElementById('latitude').value = pos.lat.toFixed(6);
            document.getElementById('longitude').value = pos.lng.toFixed(6);
        });

        // Update Input & Marker saat Peta diklik
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
        });
    </script>
</x-app-layout>