<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.dashboard') }}" class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Pengaturan Bengkel') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('owner.bengkel.update', $bengkel->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH') <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    
                    <div class="bg-gray-50 p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Informasi Umum</h3>
                        <p class="text-sm text-gray-500">Update data utama bengkel Anda agar mudah ditemukan pelanggan.</p>
                    </div>

                    <div class="p-6 space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bengkel</label>
                                <input type="text" name="nama_bengkel" value="{{ old('nama_bengkel', $bengkel->nama_bengkel) }}" 
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                                @error('nama_bengkel') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp (Aktif)</label>
                                <input type="number" name="nomer_telepon" value="{{ old('nomer_telepon', $bengkel->nomer_telepon) }}" 
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                                @error('nomer_telepon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>{{ old('alamat', $bengkel->alamat) }}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat / Catatan</label>
                            <textarea name="deskripsi" rows="2" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Sedia ban dalam Swallow, buka 24 jam...">{{ old('deskripsi', $bengkel->deskripsi) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Buka</label>
                                <input type="time" name="jam_buka" value="{{ old('jam_buka', $bengkel->jam_buka) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Tutup</label>
                                <input type="time" name="jam_tutup" value="{{ old('jam_tutup', $bengkel->jam_tutup) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Operasional</label>
                                <select name="is_open" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="1" {{ $bengkel->is_open ? 'selected' : '' }}>Buka (Online)</option>
                                    <option value="0" {{ !$bengkel->is_open ? 'selected' : '' }}>Tutup (Offline)</option>
                                </select>
                            </div>
                        </div>

                        <div class="border rounded-xl p-4 bg-gray-50">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Titik Lokasi Bengkel (Geser Pin)</label>
                            <div id="mapEdit" class="h-64 w-full rounded-lg border border-gray-300 z-0"></div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $bengkel->latitude) }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $bengkel->longitude) }}">
                            <p class="text-xs text-gray-500 mt-2">*Pastikan pin berada tepat di lokasi bengkel agar perhitungan jarak akurat.</p>
                        </div>

                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Atur Tarif Jasa</h3>
                            <p class="text-sm text-gray-500 mb-4">Tentukan harga jasa tambal ban berdasarkan jenis kendaraan dan jarak.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                                    <div class="flex items-center gap-2 mb-4 border-b border-blue-200 pb-2">
                                        <i class="fa-solid fa-motorcycle text-blue-600 text-xl"></i>
                                        <h4 class="font-bold text-blue-800">Tarif Motor</h4>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Jarak Dekat (0-5 KM)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                                <input type="number" name="harga_motor_dekat" value="{{ old('harga_motor_dekat', $bengkel->harga_motor_dekat) }}" class="pl-8 w-full rounded-lg border-blue-200 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="20000">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Jarak Jauh (5-10 KM)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                                <input type="number" name="harga_motor_jauh" value="{{ old('harga_motor_jauh', $bengkel->harga_motor_jauh) }}" class="pl-8 w-full rounded-lg border-blue-200 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="35000">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-green-50 p-5 rounded-xl border border-green-100">
                                    <div class="flex items-center gap-2 mb-4 border-b border-green-200 pb-2">
                                        <i class="fa-solid fa-car text-green-600 text-xl"></i>
                                        <h4 class="font-bold text-green-800">Tarif Mobil</h4>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Jarak Dekat (0-5 KM)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                                <input type="number" name="harga_mobil_dekat" value="{{ old('harga_mobil_dekat', $bengkel->harga_mobil_dekat) }}" class="pl-8 w-full rounded-lg border-green-200 focus:ring-green-500 focus:border-green-500 text-sm" placeholder="35000">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">Jarak Jauh (5-10 KM)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                                <input type="number" name="harga_mobil_jauh" value="{{ old('harga_mobil_jauh', $bengkel->harga_mobil_jauh) }}" class="pl-8 w-full rounded-lg border-green-200 focus:ring-green-500 focus:border-green-500 text-sm" placeholder="50000">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition transform active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil data Lat/Lng dari database (atau default jika null)
            var lat = {{ $bengkel->latitude ?? -6.200000 }};
            var lng = {{ $bengkel->longitude ?? 106.816666 }};

            var map = L.map('mapEdit').setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

            // Icon Bengkel
            var shopIcon = L.divIcon({className: 'bg-transparent', html: '<i class="fa-solid fa-store text-3xl text-red-600 drop-shadow-md"></i>', iconSize: [30, 30], iconAnchor: [15, 30]});

            // Marker Draggable (Bisa Digeser)
            var marker = L.marker([lat, lng], {icon: shopIcon, draggable: true}).addTo(map);

            // Update Input Hidden saat marker digeser
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;
            });

            // Update Input Hidden saat peta diklik
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
            });
        });
    </script>
</x-app-layout>