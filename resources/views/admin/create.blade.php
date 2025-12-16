<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
                class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-bold text-2xl text-gray-900">Tambah Data Baru</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('tambal-ban.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Informasi Bengkel</h3>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bengkel</label>
                                <input type="text" name="nama_bengkel" value="{{ old('nama_bengkel') }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tetapkan Owner</label>
                                <select name="user_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500">
                                    <option value="">-- Pilih Akun Owner --</option>
                                    @foreach ($owners as $owner)
                                        <option value="{{ $owner->id }}">{{ $owner->name }} ({{ $owner->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilih user yang akan mengelola bengkel ini.</p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bengkel</label>
                                <input type="file" name="gambar"
                                    class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select name="kategori" class="w-full rounded-lg border-gray-300 text-sm">
                                        <option value="motor">Motor</option>
                                        <option value="mobil">Mobil</option>
                                        <option value="keduanya">Keduanya</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                                    <div class="flex items-center gap-1">
                                        <input type="time" name="jam_buka"
                                            class="w-full rounded border-gray-300 text-xs px-1" required>
                                        <span>-</span>
                                        <input type="time" name="jam_tutup"
                                            class="w-full rounded border-gray-300 text-xs px-1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                                <input type="number" name="nomer_telepon" value="{{ old('nomer_telepon') }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea name="alamat" rows="3" class="w-full rounded-lg border-gray-300">{{ old('alamat') }}</textarea>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Koordinat</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="latitude" id="latitude" placeholder="Lat"
                                    class="w-full rounded-lg border-gray-300 text-sm" required>
                                <input type="text" name="longitude" id="longitude" placeholder="Lng"
                                    class="w-full rounded-lg border-gray-300 text-sm" required>
                            </div>
                            <p class="text-xs text-blue-600 mt-2">Klik peta atau ketik manual.</p>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition">
                            Simpan Data
                        </button>
                    </div>

                    <div class="lg:col-span-2 h-full min-h-[500px]">
                        <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-200 h-full flex flex-col">
                            <div class="flex justify-between items-center px-2 py-2 mb-2">
                                <span class="text-sm font-bold text-gray-700">Pilih Titik Lokasi</span>
                                <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">Mode: Input</span>
                            </div>
                            <div id="mapInput"
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
        var map = L.map('mapInput').setView([-7.4478, 112.7183], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        var marker;

        function updateMarkerFromInput() {
            var lat = parseFloat(document.getElementById('latitude').value);
            var lng = parseFloat(document.getElementById('longitude').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                var newLatLng = new L.LatLng(lat, lng);
                if (marker) marker.setLatLng(newLatLng);
                else marker = L.marker(newLatLng).addTo(map);
                map.panTo(newLatLng);
            }
        }
        document.getElementById('latitude').addEventListener('input', updateMarkerFromInput);
        document.getElementById('longitude').addEventListener('input', updateMarkerFromInput);

        map.on('click', function(e) {
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
            if (marker) marker.setLatLng(e.latlng);
            else marker = L.marker(e.latlng).addTo(map);
        });
    </script>
</x-app-layout>
