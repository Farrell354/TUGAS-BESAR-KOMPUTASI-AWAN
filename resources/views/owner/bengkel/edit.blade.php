<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.dashboard') }}" class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition shadow-sm shrink-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight truncate">
                {{ __('Pengaturan Bengkel') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center animate-fade-in-down">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-green-600"></i>
                        <span><strong class="font-bold">Berhasil!</strong> {{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('owner.bengkel.update', $bengkel->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                    
                    <div class="bg-gray-50 p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-store text-blue-600"></i> Informasi Umum
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Update data utama bengkel Anda agar mudah ditemukan pelanggan.</p>
                    </div>

                    <div class="p-6 space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Bengkel</label>
                                <input type="text" name="nama_bengkel" value="{{ old('nama_bengkel', $bengkel->nama_bengkel) }}" 
                                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" required>
                                @error('nama_bengkel') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp (Aktif)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold text-sm">+62</span>
                                    <input type="number" name="nomer_telepon" value="{{ old('nomer_telepon', $bengkel->nomer_telepon) }}" 
                                        class="w-full pl-12 rounded-xl border-gray-300 focus:ring-green-500 focus:border-green-500 transition shadow-sm" required>
                                </div>
                                @error('nomer_telepon') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" required>{{ old('alamat', $bengkel->alamat) }}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Singkat / Catatan</label>
                            <textarea name="deskripsi" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" placeholder="Contoh: Sedia ban dalam Swallow, buka 24 jam...">{{ old('deskripsi', $bengkel->deskripsi) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jam Buka</label>
                                <input type="time" name="jam_buka" value="{{ old('jam_buka', $bengkel->jam_buka) }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jam Tutup</label>
                                <input type="time" name="jam_tutup" value="{{ old('jam_tutup', $bengkel->jam_tutup) }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Toko</label>
                                <select name="is_open" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500">
                                    <option value="1" {{ $bengkel->is_open ? 'selected' : '' }}>🟢 Buka (Online)</option>
                                    <option value="0" {{ !$bengkel->is_open ? 'selected' : '' }}>🔴 Tutup (Offline)</option>
                                </select>
                            </div>
                        </div>

                        <div class="border rounded-xl p-1 bg-white shadow-sm">
                            <div class="bg-blue-50 px-4 py-2 rounded-t-lg border-b border-blue-100 flex justify-between items-center">
                                <span class="text-xs font-bold text-blue-700 flex items-center gap-1">
                                    <i class="fa-solid fa-map-pin"></i> Lokasi Bengkel
                                </span>
                                <span class="text-[10px] text-blue-500">Geser pin merah untuk update lokasi.</span>
                            </div>
                            <div id="mapEdit" class="h-64 w-full rounded-b-lg z-0"></div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $bengkel->latitude) }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $bengkel->longitude) }}">
                        </div>

                        <div class="border-t border-gray-100 pt-6 mt-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-tags text-green-600"></i> Atur Tarif Jasa
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">Tentukan harga jasa tambal ban berdasarkan jenis kendaraan dan jarak.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group hover:border-blue-300 transition">
                                    <div class="flex items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                                        <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                                            <i class="fa-solid fa-motorcycle text-xl"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-800">Tarif Motor</h4>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-1">Jarak Dekat (0-5 KM)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm font-bold">Rp</span>
                                                <input type="number" name="harga_motor_dekat" value="{{ old('harga_motor_dekat', $bengkel->harga_motor_dekat) }}" class="pl-10 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm font-bold text-gray-700" placeholder="20000">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-1">Jarak Jauh (5-10 KM)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm font-bold">Rp</span>
                                                <input type="number" name="harga_motor_jauh" value="{{ old('harga_motor_jauh', $bengkel->harga_motor_jauh) }}" class="pl-10 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm font-bold text-gray-700" placeholder="35000">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group hover:border-green-300 transition">
                                    <div class="flex items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                                        <div class="bg-green-100 p-2 rounded-lg text-green-600">
                                            <i class="fa-solid fa-car text-xl"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-800">Tarif Mobil</h4>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-1">Jarak Dekat (0-5 KM)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm font-bold">Rp</span>
                                                <input type="number" name="harga_mobil_dekat" value="{{ old('harga_mobil_dekat', $bengkel->harga_mobil_dekat) }}" class="pl-10 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm font-bold text-gray-700" placeholder="35000">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-1">Jarak Jauh (5-10 KM)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm font-bold">Rp</span>
                                                <input type="number" name="harga_mobil_jauh" value="{{ old('harga_mobil_jauh', $bengkel->harga_mobil_jauh) }}" class="pl-10 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 text-sm font-bold text-gray-700" placeholder="50000">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col md:flex-row items-center justify-end gap-3">
                        <button type="button" onclick="history.back()" class="w-full md:w-auto text-gray-600 font-bold text-sm px-4 py-2 hover:underline">Batal</button>
                        <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
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
            var lat = {{ $bengkel->latitude ?? -7.4478 }};
            var lng = {{ $bengkel->longitude ?? 112.7183 }};

            var map = L.map('mapEdit', {zoomControl: false}).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

            var iconHtml = `<div class="relative flex h-6 w-6"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-6 w-6 bg-red-600 border-2 border-white shadow-md"></span></div>`;
            var icon = L.divIcon({className: 'bg-transparent border-none', html: iconHtml, iconSize: [24, 24], iconAnchor: [12, 12]});

            var marker = L.marker([lat, lng], {icon: icon, draggable: true}).addTo(map);

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
        });
    </script>
</x-app-layout>