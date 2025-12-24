<x-app-layout>
    <script src="//unpkg.com/alpinejs" defer></script>

    <div x-data="{ sidebarOpen: true }" class="relative w-full h-[calc(100vh-65px)] bg-gray-100 overflow-hidden flex">

        <div class="flex-1 relative h-full transition-all duration-300 ease-in-out">
            
            <div class="absolute top-4 left-4 right-16 md:right-auto md:w-80 z-[999] transition-all duration-300">
                <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-lg flex items-center p-1.5 border border-white/50 ring-1 ring-black/5">
                    <div class="pl-3 pr-2 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" id="adminSearch" placeholder="Cari Bengkel..."
                        class="w-full border-none bg-transparent focus:ring-0 text-sm text-gray-700 h-9 placeholder-gray-400"
                    >
                </div>
            </div>

            <button @click="sidebarOpen = true" x-show="!sidebarOpen" x-transition 
                class="absolute top-4 right-4 z-[999] bg-white h-10 px-4 rounded-lg shadow-lg hidden md:flex items-center gap-2 text-gray-700 hover:text-blue-600 border border-gray-100 font-bold text-sm cursor-pointer">
                <i class="fa-solid fa-list-ul"></i> Daftar
            </button>

            <button @click="sidebarOpen = !sidebarOpen" class="absolute top-4 right-4 z-[999] bg-white h-12 w-12 rounded-xl shadow-lg flex items-center justify-center text-gray-700 hover:text-blue-600 md:hidden border border-gray-100 active:scale-95 transition">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>

            <div class="absolute bottom-8 left-4 z-[999] pointer-events-none">
                <div class="bg-white/90 backdrop-blur-md p-3 pr-5 rounded-2xl shadow-xl border border-white/50 flex items-center gap-3 pointer-events-auto transform transition hover:scale-105">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white h-10 w-10 rounded-xl flex items-center justify-center text-lg shadow-lg shadow-blue-500/30">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Total Terdaftar</p>
                        <h3 class="text-xl font-black text-gray-800 leading-none">{{ count($lokasi) }} <span class="text-xs font-normal text-gray-400">Titik</span></h3>
                    </div>
                </div>
            </div>

            <div id="mapAdmin" class="w-full h-full z-0 bg-gray-200"></div>
        </div>

        <div id="adminSidebar" 
             :class="sidebarOpen ? 'translate-x-0 w-80' : 'translate-x-full w-0 md:translate-x-0 md:w-0'"
             class="bg-white shadow-2xl z-[1000] flex flex-col border-l border-gray-200 transition-all duration-300 ease-in-out h-full absolute right-0 md:relative shrink-0">
            
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center shrink-0 w-80">
                <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm uppercase tracking-wide">
                    <i class="fa-solid fa-list-ul text-blue-600"></i> Daftar Bengkel
                </h3>
                <button @click="sidebarOpen = false" class="h-8 w-8 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-600 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div id="locationList" class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar bg-gray-50/30 w-80">
                </div>

            <div class="p-3 border-t border-gray-100 bg-white shrink-0 w-80">
                <a href="{{ route('tambal-ban.create') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200 transition text-sm text-center transform active:scale-95">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Lokasi Baru
                </a>
            </div>
        </div>
    </div>

    <style>
        .leaflet-popup-content-wrapper { padding: 0; border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .leaflet-popup-content { margin: 0; width: 260px !important; }
        .leaflet-container a.leaflet-popup-close-button { top: 8px; right: 8px; color: white; text-shadow: 0 1px 2px rgba(0,0,0,0.3); font-size: 18px; width: 24px; height: 24px; background: rgba(0,0,0,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .leaflet-container a.leaflet-popup-close-button:hover { background: rgba(0,0,0,0.4); color: white; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // 1. INIT MAP
        var map = L.map('mapAdmin', { zoomControl: false }).setView([-7.4478, 112.7183], 13);
        
        // Zoom control di kiri bawah
        L.control.zoom({ position: 'bottomleft' }).addTo(map);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OSM' }).addTo(map);

        var locations = @json($lokasi);
        var markers = [];

        // 2. RENDER MARKERS & SIDEBAR
        locations.forEach(point => createMarker(point));
        renderSidebarList(locations);

        function createMarker(point) {
            var editUrl = "{{ url('admin/tambal-ban') }}/" + point.id + "/edit";
            var deleteUrl = "{{ url('admin/tambal-ban') }}/" + point.id;
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Status Buka/Tutup
            var now = new Date();
            var jam = now.getHours() + ":" + ("0" + now.getMinutes()).slice(-2);
            var isOpen = (point.jam_buka <= jam && point.jam_tutup >= jam);
            var statusBadge = isOpen 
                ? '<span class="bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm flex items-center gap-1"><span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> BUKA</span>' 
                : '<span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">TUTUP</span>';

            var imgUrl = point.gambar ? `/storage/${point.gambar}` : 'https://placehold.co/300x150?text=No+Image';

            var popupContent = `
                <div class="bg-white font-sans w-[260px] pb-1">
                    <div class="h-32 w-full bg-gray-200 relative group">
                        <img src="${imgUrl}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute top-2 left-2">${statusBadge}</div>
                        <div class="absolute bottom-2 left-3 text-white">
                            <div class="text-[10px] font-bold opacity-80 uppercase tracking-wider mb-0.5">${point.kategori}</div>
                            <h4 class="font-bold text-sm leading-tight line-clamp-1">${point.nama_bengkel}</h4>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div class="text-center bg-gray-50 p-1.5 rounded-lg border border-gray-100">
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Jam Buka</p>
                                <p class="text-xs font-bold text-gray-700">${point.jam_buka?.substring(0,5)}</p>
                            </div>
                            <div class="text-center bg-gray-50 p-1.5 rounded-lg border border-gray-100">
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Jam Tutup</p>
                                <p class="text-xs font-bold text-gray-700">${point.jam_tutup?.substring(0,5)}</p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="${editUrl}" class="flex-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border border-yellow-200 py-1.5 rounded-lg text-xs font-bold text-center transition flex items-center justify-center gap-1">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Hapus permanen?');" class="flex-1">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 py-1.5 rounded-lg text-xs font-bold text-center transition flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            var marker = L.marker([point.latitude, point.longitude]).addTo(map).bindPopup(popupContent);
            markers.push({ id: point.id, marker: marker, data: point });
        }

        function renderSidebarList(data) {
            var container = document.getElementById('locationList');
            container.innerHTML = '';

            if(data.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-400 py-10 text-xs"><i class="fa-solid fa-magnifying-glass text-2xl mb-2 opacity-50"></i><br>Tidak ditemukan.</div>';
                return;
            }

            data.forEach(item => {
                var editUrl = "{{ url('admin/tambal-ban') }}/" + item.id + "/edit";
                var iconCat = item.kategori == 'mobil' ? 'fa-car' : (item.kategori == 'motor' ? 'fa-motorcycle' : 'fa-screwdriver-wrench');
                var colorCat = item.kategori == 'mobil' ? 'text-blue-600 bg-blue-50 border-blue-100' : (item.kategori == 'motor' ? 'text-green-600 bg-green-50 border-green-100' : 'text-purple-600 bg-purple-50 border-purple-100');

                var div = document.createElement('div');
                div.className = "bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-blue-400 hover:shadow-md transition cursor-pointer group relative";
                div.onclick = () => focusOnMarker(item.latitude, item.longitude, item.id);

                div.innerHTML = `
                    <div class="flex gap-3 items-center">
                        <div class="h-10 w-10 rounded-lg ${colorCat} border flex flex-shrink-0 items-center justify-center text-lg">
                            <i class="fa-solid ${iconCat}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 text-sm truncate group-hover:text-blue-600 transition">${item.nama_bengkel}</h4>
                            <p class="text-[10px] text-gray-400 mt-0.5 truncate flex items-center gap-1">
                                <i class="fa-solid fa-map-pin"></i> ${item.alamat?.substring(0,25) ?? '-'}...
                            </p>
                        </div>
                        <a href="${editUrl}" class="h-8 w-8 flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-gray-400 hover:text-blue-600 rounded-lg border border-gray-200 transition" title="Edit" onclick="event.stopPropagation()">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                `;
                container.appendChild(div);
            });
        }

        function focusOnMarker(lat, lng, id) {
            map.flyTo([lat, lng], 17, { duration: 1.2 });
            var target = markers.find(m => m.id === id);
            if(target) setTimeout(() => target.marker.openPopup(), 1200);
            
            // Di Mobile, tutup sidebar otomatis setelah klik. Cek lebar layar.
            if(window.innerWidth < 768) {
                // Karena kita pakai Alpine, kita trigger event click pada tombol burger mobile atau ubah state via JS (sedikit tricky karena scope).
                // Cara termudah: biarkan user menutup sendiri atau trigger click tombol burger jika ada
                // document.querySelector('[x-data]').__x.$data.sidebarOpen = false; // Ini cara akses Alpine data dari luar (tidak resmi)
            }
        }

        // Live Search
        document.getElementById('adminSearch').addEventListener('input', function(e) {
            var keyword = e.target.value.toLowerCase();
            var filtered = locations.filter(l => l.nama_bengkel.toLowerCase().includes(keyword) || l.kategori.toLowerCase().includes(keyword));
            renderSidebarList(filtered);
            
            markers.forEach(m => map.removeLayer(m.marker));
            markers = [];
            filtered.forEach(p => createMarker(p));
        });

        // Resize Observer agar Peta tidak grey saat sidebar toggle
        const resizeObserver = new ResizeObserver(() => {
            map.invalidateSize();
        });
        resizeObserver.observe(document.getElementById('mapAdmin'));

        // Auto Fit
        if(locations.length > 0) {
            var group = new L.featureGroup(markers.map(m => m.marker));
            map.fitBounds(group.getBounds(), { padding: [50, 50] });
        }
    </script>
</x-app-layout>