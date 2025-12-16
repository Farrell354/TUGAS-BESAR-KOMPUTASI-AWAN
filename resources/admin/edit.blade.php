<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
                class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-bold text-2xl text-gray-900">Edit Data: {{ $tambalBan->nama_bengkel }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('tambal-ban.update', $tambalBan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Informasi Bengkel</h3>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bengkel</label>
                                <input type="text" name="nama_bengkel"
                                    value="{{ old('nama_bengkel', $tambalBan->nama_bengkel) }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tetapkan Owner</label>
                                <select name="user_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500">
                                    <option value="">-- Belum Ada Owner --</option>
                                    @foreach ($owners as $owner)
                                        <option value="{{ $owner->id }}"
                                            {{ $tambalBan->user_id == $owner->id ? 'selected' : '' }}>
                                            {{ $owner->name }} ({{ $owner->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bengkel</label>
                                @if ($tambalBan->gambar)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $tambalBan->gambar) }}"
                                            class="h-32 w-full object-cover rounded-lg border">
                                    </div>
                                @endif
                                <input type="file" name="gambar"
                                    class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg">
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti foto.
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select name="kategori" class="w-full rounded-lg border-gray-300 text-sm">
                                        <option value="motor" {{ $tambalBan->kategori == 'motor' ? 'selected' : '' }}>
                                            Motor</option>
                                        <option value="mobil" {{ $tambalBan->kategori == 'mobil' ? 'selected' : '' }}>
                                            Mobil</option>
                                        <option value="keduanya"
                                            {{ $tambalBan->kategori == 'keduanya' ? 'selected' : '' }}>Keduanya
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                                    <div class="flex items-center gap-1">
                                        <input type="time" name="jam_buka" value="{{ $tambalBan->jam_buka }}"
                                            class="w-full rounded border-gray-300 text-xs px-1">
                                        <span>-</span>
                                        <input type="time" name="jam_tutup" value="{{ $tambalBan->jam_tutup }}"
                                            class="w-full rounded border-gray-300 text-xs px-1">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                                <input type="number" name="nomer_telepon"
                                    value="{{ old('nomer_telepon', $tambalBan->nomer_telepon) }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea name="alamat" rows="3" class="w-full rounded-lg border-gray-300">{{ old('alamat', $tambalBan->alamat) }}</textarea>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Koordinat</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="latitude" id="latitude" value="{{ $tambalBan->latitude }}"
                                    class="w-full rounded-lg border-gray-300 text-sm">
                                <input type="text" name="longitude" id="longitude"
                                    value="{{ $tambalBan->longitude }}"
                                    class="w-full rounded-lg border-gray-300 text-sm">
                            </div>
                            <p class="text-xs text-orange-600 mt-2 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-arrows-up-down-left-right"></i> Geser marker atau ketik manual.
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition">
                            Simpan Perubahan
                        </button>
                    </div>

                    <div class="lg:col-span-2 h-full min-h-[500px]">
                        <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-200 h-full flex flex-col">
                            <div class="flex justify-between items-center px-2 py-2 mb-2">
                                <span class="text-sm font-bold text-gray-700">Lokasi Saat Ini</span>
                                <span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded">Mode: Edit</span>
                            </div>
                            <div id="mapEdit"
                                class="flex-1 rounded-lg z-0 w-full h-full min-h-[450px] border border-gray-300"></div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var curLat = {{ $tambalBan->latitude }};
        var curLng = {{ $tambalBan->longitude }};
        var map = L.map('mapEdit').setView([curLat, curLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([curLat, curLng], {
            draggable: true
        }).addTo(map);

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

        marker.on('dragend', function(e) {
            var pos = marker.getLatLng();
            document.getElementById('latitude').value = pos.lat;
            document.getElementById('longitude').value = pos.lng;
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    </script>
</x-app-layout>
