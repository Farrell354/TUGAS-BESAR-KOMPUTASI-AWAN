<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peta Lokasi Tambal Ban</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            overflow: hidden;
        }

        #mapPublic {
            height: 100vh;
            width: 100%;
            z-index: 0;
            position: absolute;
            top: 0;
            left: 0;
        }

        /* Popup Styling */
        .leaflet-popup-content-wrapper {
            padding: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .leaflet-popup-content {
            margin: 0;
            width: 280px !important;
        }

        .leaflet-container a.leaflet-popup-close-button {
            top: 10px;
            right: 10px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 20px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .leaflet-container a.leaflet-popup-close-button:hover {
            color: white;
        }

        /* Sidebar Scrollbar */
        #sidebarContent::-webkit-scrollbar {
            width: 5px;
        }

        #sidebarContent::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebarContent::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* Pulse Animation */
        @keyframes pulse-soft {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
            }
        }

        .animate-pulse-soft {
            animation: pulse-soft 2s infinite;
        }
    </style>
</head>

<body class="antialiased relative h-screen w-screen">

<div class="absolute top-5 left-5 z-[1000] pointer-events-none flex flex-col gap-3">

        <div class="pointer-events-auto">
            <a href="{{ route('landing') }}" class="group bg-white/90 backdrop-blur-md text-gray-700 hover:text-blue-600 px-4 py-2.5 rounded-full shadow-lg flex items-center gap-3 transition-all transform hover:scale-105 border border-white/50 w-fit">
                <div class="bg-blue-100 text-blue-600 rounded-full h-8 w-8 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                <span class="font-bold text-sm pr-1">Home</span>
            </a>
        </div>

        @auth
        <div class="pointer-events-auto">
            <a href="{{ route('booking.history') }}" class="group bg-white/90 backdrop-blur-md text-gray-700 hover:text-green-600 px-4 py-2.5 rounded-full shadow-lg flex items-center gap-3 transition-all transform hover:scale-105 border border-white/50 w-fit">
                <div class="bg-green-100 text-green-600 rounded-full h-8 w-8 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <span class="font-bold text-sm pr-1">Riwayat</span>
            </a>
        </div>
        @endauth

    </div>

    <div
        class="absolute top-24 md:top-5 left-4 right-4 md:left-1/2 md:right-auto md:transform md:-translate-x-1/2 w-auto md:w-[480px] z-[1000]">
        <div
            class="bg-white/95 backdrop-blur-xl rounded-full shadow-2xl flex items-center p-1.5 pr-2 border border-white/60 transition-all focus-within:ring-2 ring-blue-400/50">
            <div class="text-gray-400 pl-4 pr-3">
                <i class="fa-solid fa-magnifying-glass text-lg"></i>
            </div>
            <input type="text" id="searchInput" placeholder="Cari bengkel atau ketik 'Terdekat'..."
                class="flex-1 outline-none text-gray-700 text-sm bg-transparent py-3 placeholder-gray-400">

            <div class="h-6 w-px bg-gray-200 mx-2"></div>

            <button onclick="findNearest()"
                class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-5 py-2.5 rounded-full text-sm font-bold hover:shadow-lg hover:to-blue-600 transition flex items-center gap-2 transform active:scale-95">
                <i class="fa-solid fa-location-crosshairs"></i> <span class="hidden xs:inline">Terdekat</span>
            </button>
        </div>
    </div>

    <div class="absolute top-5 right-20 z-[1000] pointer-events-none hidden sm:block">
        <div
            class="pointer-events-auto bg-white/90 backdrop-blur-md px-2 pl-4 py-1.5 rounded-full shadow-lg flex items-center gap-3 border border-white/50">
            @if (Route::has('login'))
                @auth
                    <div class="text-right leading-tight mr-1">
                        <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold">User</span>
                        <span
                            class="block font-bold text-gray-800 text-sm truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-50 text-red-500 h-9 w-9 flex items-center justify-center rounded-full hover:bg-red-500 hover:text-white transition"
                            title="Logout">
                            <i class="fa-solid fa-power-off"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:underline px-3">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Login
                    </a>
                @endauth
            @endif
        </div>
    </div>

    <button onclick="toggleSidebar()"
        class="absolute top-5 right-5 z-[3000] bg-blue-600 text-white h-12 w-12 rounded-full shadow-xl border-2 border-white hover:bg-blue-700 hover:scale-110 transition flex items-center justify-center animate-pulse-soft">
        <i class="fa-solid fa-list-ul text-lg"></i>
    </button>

    <div id="mapPublic"></div>

    <div id="rightSidebar"
        class="fixed top-0 right-0 h-full w-full sm:w-[400px] bg-white shadow-2xl z-[2000] transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="bg-blue-600 p-6 text-white shadow-md relative overflow-hidden">
            <i class="fa-solid fa-map-location-dot absolute -right-4 -bottom-6 text-8xl text-white/10"></i>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <h3 class="font-bold text-xl">Daftar Lokasi</h3>
                    <p class="text-sm text-blue-100 mt-1"><span id="resultCount"
                            class="font-bold bg-white/20 px-2 py-0.5 rounded">0</span> Bengkel Ditemukan</p>
                </div>
                <button onclick="toggleSidebar()"
                    class="bg-white/20 hover:bg-white/30 text-white h-8 w-8 rounded-full flex items-center justify-center transition backdrop-blur-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        <div id="sidebarContent" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50"></div>
        <div class="p-4 border-t border-gray-200 bg-white text-center">
            <p class="text-xs text-gray-400 font-medium">© {{ date('Y') }} TambalFinder GIS</p>
        </div>
    </div>

    <script>
        // 1. Config Map
        var map = L.map('mapPublic', {zoomControl: false}).setView([-7.4478, 112.7183], 13);
        L.control.zoom({ position: 'bottomleft' }).addTo(map);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

        // VARIABEL GLOBAL
        var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        var loginUrl = "{{ route('login') }}";
        var locations = @json($lokasi);
        var markers = [];
        var userMarker = null;
        var currentList = locations; // Menyimpan state list saat ini (untuk fitur back)

        // Helper WA
        function formatWA(nomer) {
            if (!nomer) return null;
            let number = nomer.toString().replace(/\D/g, '');
            if (number.startsWith('0')) { number = '62' + number.slice(1); }
            return number;
        }

        // FUNGSI HANDLE SUBMIT REVIEW (AJAX)
        function submitReview(event, pointId) {
            event.preventDefault(); // Mencegah reload halaman

            var form = event.target;
            var formData = new FormData(form);
            var btn = form.querySelector('button');

            // Ubah tombol jadi loading
            var originalText = btn.innerText;
            btn.innerText = 'Mengirim...';
            btn.disabled = true;

            fetch("{{ route('review.store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // 1. Update Data Lokal (locations)
                    var pointIndex = locations.findIndex(l => l.id == pointId);
                    if(pointIndex !== -1) {
                        // Tambahkan review baru ke array lokal
                        if (!locations[pointIndex].reviews) locations[pointIndex].reviews = [];
                        locations[pointIndex].reviews.push(data.data);

                        // 2. Render Ulang Sidebar agar review muncul
                        showDetailSidebar(locations[pointIndex]);

                        // 3. Notifikasi Sukses
                        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        Toast.fire({ icon: 'success', title: 'Ulasan diterbitkan!' });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Gagal mengirim ulasan.', 'error');
                btn.innerText = originalText;
                btn.disabled = false;
            });
        }

        // ============================================================
        // FUNGSI 1: TAMPILKAN DETAIL DI SIDEBAR (FITUR BARU)
        // ============================================================
        function showDetailSidebar(point) {
            var container = document.getElementById('sidebarContent');
            var sidebar = document.getElementById('rightSidebar');

            if (sidebar.classList.contains('translate-x-full')) sidebar.classList.remove('translate-x-full');

            // Logic Status
            var now = new Date();
            var jamSekarang = ("0" + now.getHours()).slice(-2) + ":" + ("0" + now.getMinutes()).slice(-2);
            var isOpen = (point.jam_buka && point.jam_tutup) && (jamSekarang >= point.jam_buka && jamSekarang <= point.jam_tutup);
            var statusBadge = isOpen ? '<span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded border border-green-200">BUKA SEKARANG</span>' : '<span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded border border-red-200">TUTUP</span>';

            var imgUrl = point.gambar ? `/storage/${point.gambar}` : 'https://placehold.co/400x250?text=No+Image';
            var waNumber = formatWA(point.nomer_telepon);
            var waLink = waNumber ? `https://wa.me/${waNumber}` : '#';
            var mapLink = `https://www.google.com/maps/dir/?api=1&destination=${point.latitude},${point.longitude}`;
            var orderUrl = "{{ url('/booking') }}/" + point.id;

            // Hitung Rating & Render Review List
            var count = point.reviews ? point.reviews.length : 0;
            var rating = 0;
            var reviewsHtml = '';

            if (count > 0) {
                var total = point.reviews.reduce((acc, rev) => acc + rev.rating, 0);
                rating = (total / count).toFixed(1);

                // Urutkan review terbaru di atas (reverse)
                var sortedReviews = [...point.reviews].reverse();

                reviewsHtml = sortedReviews.map(r => `
                    <div class="border-b border-gray-100 pb-2 mb-2 last:border-0">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-gray-700">${r.user ? r.user.name : 'Anda'}</span>
                            <span class="text-xs text-yellow-500"><i class="fa-solid fa-star"></i> ${r.rating}</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">"${r.komentar || ''}"</p>
                    </div>
                `).join('');
            } else {
                reviewsHtml = '<p class="text-xs text-gray-400 italic text-center py-2">Belum ada ulasan. Jadilah yang pertama!</p>';
            }

            var stars = '';
            for(let i=1; i<=5; i++) stars += i <= rating ? '<i class="fa-solid fa-star text-yellow-400"></i>' : '<i class="fa-regular fa-star text-gray-300"></i>';

            // Form Review (AJAX)
            var reviewForm = isLoggedIn ? `
                <div class="mt-4 bg-gray-50 p-3 rounded-xl border border-gray-200">
                    <h4 class="font-bold text-sm text-gray-700 mb-2">Tulis Ulasan</h4>
                    <form onsubmit="submitReview(event, ${point.id})" class="space-y-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="tambal_ban_id" value="${point.id}">
                        <div class="flex items-center gap-2">
                            <select name="rating" class="text-xs border-gray-300 rounded p-1 w-20 focus:ring-blue-500">
                                <option value="5">5 ⭐</option><option value="4">4 ⭐</option><option value="3">3 ⭐</option><option value="2">2 ⭐</option><option value="1">1 ⭐</option>
                            </select>
                            <input type="text" name="komentar" class="flex-1 text-xs border-gray-300 rounded p-1 focus:ring-blue-500" placeholder="Komentar..." required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white text-xs py-1.5 rounded font-bold hover:bg-blue-700 transition">Kirim Ulasan</button>
                    </form>
                </div>
            ` : `
                <div class="mt-4 text-center">
                    <a href="${loginUrl}" class="text-xs text-blue-600 font-bold hover:underline">Login untuk menulis ulasan</a>
                </div>
            `;

            container.innerHTML = `
                <div class="animate-pulse-soft pb-10">
                    <button onclick="renderSidebar(currentList)" class="mb-3 flex items-center gap-2 text-gray-500 hover:text-blue-600 transition text-sm font-bold">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </button>

                    <div class="h-48 w-full rounded-xl overflow-hidden relative mb-4 shadow-sm group">
                        <img src="${imgUrl}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        <div class="absolute bottom-2 left-2 bg-black/60 text-white px-2 py-1 rounded text-xs font-bold uppercase backdrop-blur-sm">
                            ${point.kategori}
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 leading-tight mb-1">${point.nama_bengkel}</h2>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex text-xs">${stars}</div>
                        <span class="text-xs text-gray-500">(${count} ulasan)</span>
                        ${statusBadge}
                    </div>

                    <div class="space-y-2 mb-4 text-sm text-gray-600 bg-white p-3 rounded-lg border border-gray-100">
                        <p class="flex gap-2"><i class="fa-solid fa-map-pin text-red-500 mt-1"></i> ${point.alamat ?? '-'}</p>
                        <p class="flex gap-2"><i class="fa-regular fa-clock text-blue-500 mt-1"></i> ${point.jam_buka?.substring(0,5)} - ${point.jam_tutup?.substring(0,5)} WIB</p>
                        <p class="flex gap-2"><i class="fa-solid fa-phone text-green-500 mt-1"></i> ${point.nomer_telepon}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <a href="${waLink}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm font-bold text-center flex items-center justify-center gap-2 shadow-sm transition">
                            <i class="fa-brands fa-whatsapp text-lg"></i> Chat
                        </a>
                        <a href="${mapLink}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-bold text-center flex items-center justify-center gap-2 shadow-sm transition">
                            <i class="fa-solid fa-diamond-turn-right text-lg"></i> Rute
                        </a>
                        <a href="${orderUrl}" class="col-span-2 bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg text-sm font-bold text-center flex items-center justify-center gap-2 shadow-md transition">
                            <i class="fa-solid fa-cart-shopping text-lg"></i> PESAN JASA KE RUMAH
                        </a>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fa-regular fa-comments"></i> Ulasan Pengguna
                        </h3>
                        <div class="max-h-60 overflow-y-auto pr-1 custom-scrollbar space-y-2">
                            ${reviewsHtml}
                        </div>
                        ${reviewForm}
                    </div>
                </div>
            `;
        }

        // ============================================================
        // 2. CREATE MARKER (UPDATE: Klik Marker -> Buka Sidebar)
        // ============================================================
        function createMarker(point) {
            // Kita sederhanakan popup marker karena detail lengkap ada di sidebar
            var smallPopup = `
                <div class="text-center p-1">
                    <h3 class="font-bold text-gray-800 text-sm">${point.nama_bengkel}</h3>
                    <p class="text-xs text-gray-500 mb-2">${point.alamat?.substring(0,30)}...</p>
                    <button onclick="focusLocation(${point.latitude}, ${point.longitude}); showDetailSidebar(locations.find(l => l.id == ${point.id}))"
                        class="bg-blue-600 text-white text-xs px-3 py-1 rounded-full w-full hover:bg-blue-700">
                        Lihat Detail
                    </button>
                </div>
            `;

            var marker = L.marker([point.latitude, point.longitude]).addTo(map).bindPopup(smallPopup);

            // Event Klik Marker -> Buka Sidebar Detail
            marker.on('click', function() {
                showDetailSidebar(point);
                // Di mobile, pastikan sidebar terbuka
                document.getElementById('rightSidebar').classList.remove('translate-x-full');
            });

            markers.push({ id: point.id, marker: marker, data: point });
        }

        locations.forEach(point => createMarker(point));

        // ============================================================
        // 3. RENDER SIDEBAR LIST (DEFAULT VIEW)
        // ============================================================
        function renderSidebar(data) {
            currentList = data; // Simpan state list saat ini
            var container = document.getElementById('sidebarContent');
            container.innerHTML = '';
            document.getElementById('resultCount').innerText = data.length;

            if(data.length === 0) {
                container.innerHTML = `<div class="flex flex-col items-center justify-center py-12 text-gray-400"><i class="fa-solid fa-magnifying-glass text-2xl mb-2"></i><p class="text-sm">Tidak ada lokasi.</p></div>`;
                return;
            }

            data.forEach((item, i) => {
                var distDisplay = item.distance ? (item.distance < 1 ? Math.round(item.distance * 1000) + " m" : item.distance.toFixed(1) + " km") : '';
                var distBadge = item.distance ? `<span class="absolute top-3 right-3 bg-orange-50 text-orange-600 text-xs font-bold px-2 py-1 rounded-md border border-orange-100"><i class="fa-solid fa-person-walking"></i> ${distDisplay}</span>` : '';

                var div = document.createElement('div');
                div.className = "relative group bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:border-blue-400 hover:shadow-md transition-all cursor-pointer";

                // KLIK LIST -> ZOOM & BUKA DETAIL
                div.onclick = () => {
                    focusLocation(item.latitude, item.longitude);
                    showDetailSidebar(item);
                };

                div.innerHTML = `
                    <div class="flex items-start gap-3 pr-16">
                        <div class="bg-blue-50 text-blue-600 font-bold h-8 w-8 flex-shrink-0 flex items-center justify-center rounded-lg text-sm border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition">${i+1}</div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition leading-tight">${item.nama_bengkel}</h4>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">${item.alamat ?? '-'}</p>
                        </div>
                    </div>
                    ${distBadge}
                    <div class="mt-2 text-[10px] text-blue-500 font-bold text-right group-hover:underline">Lihat Detail &rarr;</div>
                `;
                container.appendChild(div);
            });
        }

        // ============================================================
        // 4. UTILITIES & LOGIC LAINNYA
        // ============================================================
        function findNearest() {
            if (!navigator.geolocation) { Swal.fire('Error', 'Browser tidak support GPS', 'error'); return; }
            Swal.fire({ title: 'Mencari Lokasi...', didOpen: () => Swal.showLoading() });

            navigator.geolocation.getCurrentPosition((pos) => {
                Swal.close();
                var lat = pos.coords.latitude, lng = pos.coords.longitude;
                if(userMarker) map.removeLayer(userMarker);
                userMarker = L.marker([lat, lng], {icon: L.divIcon({className: 'bg-transparent', html: `<div class="relative flex h-4 w-4"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span><span class="relative inline-flex rounded-full h-4 w-4 bg-blue-600 border-2 border-white shadow-md"></span></div>`})}).addTo(map).bindPopup('<b class="text-blue-600">Lokasi Anda</b>');

                var results = locations.map(loc => ({ ...loc, distance: getDist(lat, lng, loc.latitude, loc.longitude) })).sort((a, b) => a.distance - b.distance);
                renderSidebar(results);
                openSidebar();

                if(results.length > 0) {
                    var bounds = L.latLngBounds([[lat, lng], [results[0].latitude, results[0].longitude]]);
                    map.fitBounds(bounds, {padding: [50, 50], maxZoom: 16});
                }
            }, (err) => { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pastikan GPS aktif.' }); });
        }

        function getDist(lat1, lon1, lat2, lon2) {
            var R = 6371, dLat = (lat2-lat1)*(Math.PI/180), dLon = (lon2-lon1)*(Math.PI/180);
            var a = Math.sin(dLat/2)**2 + Math.cos(lat1*(Math.PI/180)) * Math.cos(lat2*(Math.PI/180)) * Math.sin(dLon/2)**2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        }

        function focusLocation(lat, lng) {
            map.flyTo([lat, lng], 17, { duration: 1.2 });
            // Di desktop sidebar tetap buka, di mobile biar user liat detail
            if(window.innerWidth < 640) openSidebar();
        }

        var sidebar = document.getElementById('rightSidebar');
        function toggleSidebar() { sidebar.classList.contains('translate-x-full') ? openSidebar() : closeSidebar(); }
        function openSidebar() { sidebar.classList.remove('translate-x-full'); }
        function closeSidebar() { sidebar.classList.add('translate-x-full'); }

        document.getElementById('searchInput').addEventListener('input', (e) => {
            var key = e.target.value.toLowerCase();
            const triggerKeywords = ['terdekat', 'dekat', 'near', 'nearest', 'lokasi saya'];
            if (triggerKeywords.some(trigger => key.includes(trigger))) {
                findNearest(); document.getElementById('searchInput').blur(); return;
            }
            var res = locations.filter(l => l.nama_bengkel.toLowerCase().includes(key));
            renderSidebar(res);
            if(key.length > 0) openSidebar();
        });

        // Render Awal
        renderSidebar(locations);

        // FITUR AUTO-FIT
        if (markers.length > 0) {
            var group = new L.featureGroup(markers.map(m => m.marker));
            map.fitBounds(group.getBounds(), { padding: [50, 50] });
        }

        // Notifikasi
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                let nav = performance.getEntriesByType("navigation");
                if (nav.length > 0 && nav[0].type === 'back_forward') return;
                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif
        });
    </script>
</body>

</html>
