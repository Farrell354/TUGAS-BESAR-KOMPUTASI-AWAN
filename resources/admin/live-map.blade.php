<x-app-layout>
    <div class="relative w-full h-[calc(100vh-65px)] bg-gray-100 overflow-hidden">

        <div class="absolute top-4 left-4 z-[1000] w-72 md:w-96">
            <div class="bg-white rounded-lg shadow-lg flex items-center p-1 border border-gray-200">
                <div class="pl-3 pr-2 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" id="adminSearch" placeholder="Cari Nama, Kategori, atau Alamat..."
                    class="w-full border-none focus:ring-0 text-sm text-gray-700 h-10"
                >
            </div>
        </div>

        <div class="absolute bottom-6 left-4 z-[1000] bg-white p-4 rounded-xl shadow-lg border border-gray-200 flex items-center gap-4 group hover:scale-105 transition">
            <div class="bg-blue-600 text-white h-12 w-12 rounded-full flex items-center justify-center text-xl shadow-md">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Lokasi</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ count($lokasi) }} <span class="text-sm text-gray-400 font-normal">Titik</span></h3>
            </div>
        </div>

        <div class="absolute top-4 right-4 bottom-6 w-80 bg-white rounded-xl shadow-2xl z-[1000] flex flex-col border border-gray-200 transform transition-transform duration-300" id="adminSidebar">
            <div class="p-4 border-b border-gray-100 bg-gray-50 rounded-t-xl flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-list-ul text-blue-600"></i> Daftar Bengkel
                </h3>
                <button onclick="toggleSidebar()" class="text-gray-400 hover:text-gray-600 lg:hidden">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div id="locationList" class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar">
                </div>

            <div class="p-3 border-t border-gray-100">
                <a href="{{ route('tambal-ban.create') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-md transition text-sm text-center">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Lokasi
                </a>
            </div>
        </div>

        <button onclick="toggleSidebar()" class="absolute top-4 right-4 z-[900] bg-white p-3 rounded-lg shadow-lg lg:hidden text-gray-700">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div id="mapAdmin" class="w-full h-full z-0"></div>
    </div>

    <style>
        .leaflet-popup-content-wrapper { padding: 0; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border: none; }
        .leaflet-popup-content { margin: 0; width: 280px !important; }
        .leaflet-container a.leaflet-popup-close-button { top: 8px; right: 8px; color: white; text-shadow: 0 1px 3px rgba(0,0,0,0.5); font-size: 20px; z-index: 20; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // 1. Init Map
        var map = L.map('mapAdmin', { zoomControl: false }).setView([-7.4478, 112.7183], 13);
        L.control.zoom({ position: 'bottomright' }).addTo(map);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

        var locations = @json($lokasi);
        var markers = [];

        // 2. Render Markers & Sidebar
        locations.forEach(point => createMarker(point));
        renderSidebarList(locations);

        function createMarker(point) {
            // URL Edit & Delete
            var editUrl = "{{ url('admin/tambal-ban') }}/" + point.id + "/edit";
            var deleteUrl = "{{ url('admin/tambal-ban') }}/" + point.id;
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Logika Status Buka/Tutup
            var now = new Date();
            var jamSekarang = now.getHours() + ":" + now.getMinutes();
            var isOpen = (point.jam_buka <= jamSekarang && point.jam_tutup >= jamSekarang);
            var statusBadge = isOpen
                ? '<span class="bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">BUKA</span>'
                : '<span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">TUTUP</span>';

            // Ikon Kategori
            var iconCat = point.kategori == 'mobil' ? '<i class="fa-solid fa-car"></i>' : (point.kategori == 'motor' ? '<i class="fa-solid fa-motorcycle"></i>' : '<i class="fa-solid fa-screwdriver-wrench"></i>');

            // Gambar
            var imgUrl = point.gambar ? `/storage/${point.gambar}` : 'https://placehold.co/300x150?text=No+Image';

            // HTML Popup Admin (Lengkap)
            var popupContent = `
                <div class="bg-white font-sans w-[280px]">
                    <div class="h-32 w-full bg-gray-200 relative">
                        <img src="${imgUrl}" class="w-full h-full object-cover">
                        <div class="absolute top-2 left-2">${statusBadge}</div>
                        <div class="absolute bottom-2 right-2 bg-black/60 text-white px-2 py-1 rounded text-xs backdrop-blur-sm">
                            ${iconCat} ${point.kategori.toUpperCase()}
                        </div>
                    </div>

                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 text-base leading-tight mb-1">${point.nama_bengkel}</h4>
                        <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                            <i class="fa-solid fa-clock text-blue-500"></i> ${point.jam_buka?.substring(0,5)} - ${point.jam_tutup?.substring(0,5)}
                        </p>

                        <p class="text-sm text-gray-600 mb-2 flex gap-2 items-start bg-gray-50 p-2 rounded border border-gray-100">
                            <i class="fa-solid fa-map-pin text-red-500 mt-0.5"></i>
                            <span class="line-clamp-2 text-xs">${point.alamat ?? 'Alamat kosong'}</span>
                        </p>

                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <a href="${editUrl}" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 py-2 rounded-lg text-xs font-bold text-center transition flex items-center justify-center gap-1 border border-yellow-200">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Hapus permanen?');" class="w-full">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-2 rounded-lg text-xs font-bold text-center transition flex items-center justify-center gap-1 border border-red-200">
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

        // 3. Render Sidebar List
        function renderSidebarList(data) {
            var container = document.getElementById('locationList');
            container.innerHTML = '';

            if(data.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-400 py-10 text-sm"><i class="fa-solid fa-box-open text-2xl mb-2"></i><br>Tidak ada data.</div>';
                return;
            }

            data.forEach(item => {
                var editUrl = "{{ url('admin/tambal-ban') }}/" + item.id + "/edit";

                // Icon Kategori di List
                var iconCat = item.kategori == 'mobil' ? 'fa-car' : (item.kategori == 'motor' ? 'fa-motorcycle' : 'fa-screwdriver-wrench');
                var colorCat = item.kategori == 'mobil' ? 'text-blue-500 bg-blue-50' : (item.kategori == 'motor' ? 'text-green-500 bg-green-50' : 'text-purple-500 bg-purple-50');

                var div = document.createElement('div');
                div.className = "bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-blue-400 hover:shadow-md transition cursor-pointer group relative overflow-hidden";
                div.onclick = () => focusOnMarker(item.latitude, item.longitude, item.id);

                div.innerHTML = `
                    <div class="flex gap-3 items-start relative z-10">
                        <div class="h-10 w-10 rounded-full ${colorCat} flex flex-shrink-0 items-center justify-center text-lg border border-gray-100">
                            <i class="fa-solid ${iconCat}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 text-sm truncate group-hover:text-blue-600 transition">${item.nama_bengkel}</h4>
                            <p class="text-xs text-gray-500 mt-0.5 truncate"><i class="fa-solid fa-clock text-[10px]"></i> ${item.jam_buka?.substring(0,5)} - ${item.jam_tutup?.substring(0,5)}</p>
                        </div>
                        <a href="${editUrl}" class="h-8 w-8 flex items-center justify-center bg-gray-50 hover:bg-blue-100 text-gray-400 hover:text-blue-600 rounded-lg transition" title="Edit" onclick="event.stopPropagation()">
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
            if(window.innerWidth < 1024) toggleSidebar();
        }

        // Search Filter
        document.getElementById('adminSearch').addEventListener('input', function(e) {
            var keyword = e.target.value.toLowerCase();
            var filtered = locations.filter(l =>
                l.nama_bengkel.toLowerCase().includes(keyword) ||
                l.kategori.toLowerCase().includes(keyword)
            );
            renderSidebarList(filtered);

            // Filter Marker
            markers.forEach(m => map.removeLayer(m.marker));
            markers = [];
            filtered.forEach(p => createMarker(p)); // Re-create visible markers
        });

        function toggleSidebar() {
            var sb = document.getElementById('adminSidebar');
            if(sb.classList.contains('translate-x-full')) sb.classList.remove('translate-x-full');
            else sb.classList.toggle('hidden');
        }

        // Auto Fit
        if(locations.length > 0) {
            var group = new L.featureGroup(markers.map(m => m.marker));
            map.fitBounds(group.getBounds(), { padding: [50, 50] });
        }
    </script>
</x-app-layout>
