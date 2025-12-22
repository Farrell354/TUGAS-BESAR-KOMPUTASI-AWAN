<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Peta Lokasi Tambal Ban</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; background: #f3f4f6; overflow: hidden; height: 100dvh; width: 100vw; }
        #mapPublic { height: 100%; width: 100%; z-index: 0; position: absolute; top: 0; left: 0; }
        
        @media (max-width: 640px) { .leaflet-control-zoom { display: none; } }

        .leaflet-popup-content-wrapper { padding: 0; border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .leaflet-popup-content { margin: 0; width: 240px !important; }
        .leaflet-container a.leaflet-popup-close-button { top: 8px; right: 8px; color: white; text-shadow: 0 1px 2px rgba(0,0,0,0.3); }
        
        #sidebarContent::-webkit-scrollbar { width: 4px; }
        #sidebarContent::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        .animate-pulse-soft { animation: pulse-soft 2s infinite; }
        @keyframes pulse-soft { 0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(37, 99, 235, 0); } }
    </style>
</head>

<body class="antialiased relative">

    <div class="absolute top-4 left-4 z-[1000] pointer-events-none flex flex-row md:flex-col gap-2 md:gap-3">
        
        <div class="pointer-events-auto"> <a href="{{ route('landing') }}" class="group bg-white/90 backdrop-blur-md text-gray-700 hover:text-blue-600 px-2.5 py-2 md:px-4 md:py-2.5 rounded-full shadow-md flex items-center gap-0 md:gap-3 transition-all transform hover:scale-105 border border-white/50">
                <div class="bg-blue-100 text-blue-600 rounded-full h-8 w-8 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                <span class="font-bold text-sm pr-1 hidden md:block">Home</span>
            </a>
        </div>

        @auth
        <div class="pointer-events-auto"> <a href="{{ route('booking.history') }}" class="group bg-white/90 backdrop-blur-md text-gray-700 hover:text-green-600 px-2.5 py-2 md:px-4 md:py-2.5 rounded-full shadow-md flex items-center gap-0 md:gap-3 transition-all transform hover:scale-105 border border-white/50">
                <div class="bg-green-100 text-green-600 rounded-full h-8 w-8 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <span class="font-bold text-sm pr-1 hidden md:block">Riwayat</span>
            </a>
        </div>
        @endauth
    </div>

    <div class="absolute top-20 md:top-5 left-1/2 transform -translate-x-1/2 w-[95%] md:w-[480px] z-[1000] transition-all duration-300 pointer-events-none">
        <div class="pointer-events-auto bg-white/95 backdrop-blur-xl rounded-full shadow-xl flex items-center p-1.5 pr-2 border border-white/60 focus-within:ring-2 ring-blue-400/50 w-full">
            
            <div class="text-gray-400 pl-3 md:pl-4 pr-2">
                <i class="fa-solid fa-magnifying-glass text-base md:text-lg"></i>
            </div>
            
            <input type="text" id="searchInput" placeholder="Cari bengkel..."
                class="flex-1 outline-none text-gray-700 text-sm bg-transparent py-2.5 md:py-3 placeholder-gray-400 min-w-0">

            <div class="h-6 w-px bg-gray-200 mx-2 hidden sm:block"></div>

            <button onclick="findNearest()"
                class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-3 py-2 md:px-5 md:py-2.5 rounded-full text-xs md:text-sm font-bold hover:shadow-lg hover:to-blue-600 transition flex items-center gap-2 transform active:scale-95 whitespace-nowrap shrink-0">
                <i class="fa-solid fa-location-crosshairs"></i> 
                <span class="hidden sm:inline">Terdekat</span>
            </button>
        </div>
    </div>

    <div class="absolute top-5 right-20 z-[1000] pointer-events-none hidden md:block">
        <div class="pointer-events-auto bg-white/90 backdrop-blur-md px-2 pl-4 py-1.5 rounded-full shadow-lg flex items-center gap-3 border border-white/50">
            @if (Route::has('login'))
                @auth
                    <div class="text-right leading-tight mr-1">
                        <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold">User</span>
                        <span class="block font-bold text-gray-800 text-sm truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex items-center">
                        @csrf
                        <button type="submit" class="bg-red-50 text-red-500 h-9 w-9 flex items-center justify-center rounded-full hover:bg-red-500 hover:text-white transition shadow-sm cursor-pointer" title="Logout">
                            <i class="fa-solid fa-power-off"></i>
                        </button>
                    </form>

                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:underline px-3 pointer-events-auto">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </a>
                @endauth
            @endif
        </div>
    </div>

    <button onclick="toggleSidebar()"
        class="absolute top-4 right-4 z-[3000] bg-blue-600 text-white h-10 w-10 md:h-12 md:w-12 rounded-full shadow-xl border-2 border-white hover:bg-blue-700 hover:scale-110 transition flex items-center justify-center animate-pulse-soft pointer-events-auto">
        <i class="fa-solid fa-list-ul text-base md:text-lg"></i>
    </button>

    <div id="mapPublic"></div>

    <div id="rightSidebar"
        class="fixed top-0 right-0 h-[100dvh] w-full sm:w-[400px] bg-white shadow-2xl z-[2000] transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        
        <div class="bg-blue-600 p-5 text-white shadow-md relative overflow-hidden shrink-0">
            <i class="fa-solid fa-map-location-dot absolute -right-4 -bottom-6 text-8xl text-white/10"></i>
            
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <h3 class="font-bold text-lg md:text-xl">Daftar Lokasi</h3>
                    <p class="text-xs md:text-sm text-blue-100 mt-1"><span id="resultCount" class="font-bold bg-white/20 px-2 py-0.5 rounded">0</span> Ditemukan</p>
                </div>
                <button onclick="toggleSidebar()" class="bg-white/20 hover:bg-white/30 text-white h-8 w-8 rounded-full flex items-center justify-center transition backdrop-blur-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="mt-4 pt-4 border-t border-blue-500/50 md:hidden">
                @auth
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs text-blue-200">Halo,</p>
                                <p class="text-sm font-bold truncate max-w-[120px]">{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-4 py-2 rounded-lg font-bold shadow transition flex items-center gap-2">
                                <i class="fa-solid fa-power-off"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block bg-white text-blue-600 text-center py-2 rounded-lg text-sm font-bold hover:bg-blue-50">
                        Login / Register
                    </a>
                @endauth
            </div>
        </div>

        <div id="sidebarContent" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50"></div>
        
        <div class="p-3 border-t border-gray-200 bg-white text-center shrink-0">
            <p class="text-[10px] text-gray-400 font-medium">© {{ date('Y') }} TambalFinder GIS</p>
        </div>
    </div>

    <script>
        var map = L.map('mapPublic', { zoomControl: false, tap: false }).setView([-7.4478, 112.7183], 13);
        L.control.zoom({ position: 'bottomleft' }).addTo(map);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

        var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        var loginUrl = "{{ route('login') }}";
        var locations = @json($lokasi);
        var markers = [];
        var userMarker = null;
        var currentList = locations;

        function formatWA(nomer) {
            if (!nomer) return null;
            let number = nomer.toString().replace(/\D/g, '');
            if (number.startsWith('0')) { number = '62' + number.slice(1); }
            return number;
        }

        // Logic Review
        function submitReview(event, pointId) {
            event.preventDefault();
            var form = event.target;
            var formData = new FormData(form);
            var btn = form.querySelector('button');
            var originalText = btn.innerText;
            btn.innerText = '...'; btn.disabled = true;

            fetch("{{ route('review.store') }}", {
                method: "POST", body: formData, headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    var idx = locations.findIndex(l => l.id == pointId);
                    if(idx !== -1) {
                        if (!locations[idx].reviews) locations[idx].reviews = [];
                        locations[idx].reviews.push(data.data);
                        showDetailSidebar(locations[idx]);
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Ulasan terkirim!', showConfirmButton: false, timer: 2000 });
                    }
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Gagal kirim ulasan', 'error');
                btn.innerText = originalText; btn.disabled = false;
            });
        }

        function showDetailSidebar(point) {
            var container = document.getElementById('sidebarContent');
            var sidebar = document.getElementById('rightSidebar');
            if (sidebar.classList.contains('translate-x-full')) sidebar.classList.remove('translate-x-full');

            var now = new Date();
            var jamSekarang = ("0" + now.getHours()).slice(-2) + ":" + ("0" + now.getMinutes()).slice(-2);
            var isOpen = (point.jam_buka && point.jam_tutup) && (jamSekarang >= point.jam_buka && jamSekarang <= point.jam_tutup);
            var statusBadge = isOpen ? '<span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded border border-green-200">BUKA</span>' : '<span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded border border-red-200">TUTUP</span>';
            var imgUrl = point.gambar ? `/storage/${point.gambar}` : 'https://placehold.co/400x250?text=No+Image';
            
            var count = point.reviews ? point.reviews.length : 0;
            var rating = count > 0 ? (point.reviews.reduce((a,b)=>a+b.rating,0)/count).toFixed(1) : 0;
            var stars = ''; for(let i=1; i<=5; i++) stars += i <= rating ? '<i class="fa-solid fa-star text-yellow-400"></i>' : '<i class="fa-regular fa-star text-gray-300"></i>';

            var reviewsHtml = count > 0 ? [...point.reviews].reverse().map(r => `
                <div class="border-b border-gray-100 pb-2 mb-2">
                    <div class="flex justify-between"><span class="font-bold text-xs text-gray-700">${r.user ? r.user.name : 'Anda'}</span><span class="text-xs text-yellow-500">★ ${r.rating}</span></div>
                    <p class="text-xs text-gray-500 italic">"${r.komentar || ''}"</p>
                </div>
            `).join('') : '<p class="text-xs text-gray-400 text-center italic">Belum ada ulasan.</p>';

            var reviewForm = isLoggedIn ? `
                 <div class="mt-3 bg-gray-50 p-2 rounded-lg border border-gray-200">
                    <form onsubmit="submitReview(event, ${point.id})" class="space-y-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="tambal_ban_id" value="${point.id}">
                        <div class="flex gap-1">
                            <select name="rating" class="text-xs border-gray-300 rounded p-1"><option value="5">5★</option><option value="4">4★</option><option value="3">3★</option><option value="2">2★</option><option value="1">1★</option></select>
                            <input type="text" name="komentar" class="flex-1 text-xs border-gray-300 rounded p-1" placeholder="Komentar..." required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white text-xs py-1 rounded font-bold">Kirim</button>
                    </form>
                 </div>` : `<div class="mt-2 text-center"><a href="${loginUrl}" class="text-xs text-blue-600 font-bold">Login untuk ulasan</a></div>`;

            container.innerHTML = `
                <button onclick="renderSidebar(currentList)" class="mb-3 text-xs font-bold text-gray-500 hover:text-blue-600 flex items-center gap-1"><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                <div class="h-40 w-full rounded-xl overflow-hidden relative mb-3 shadow-sm"><img src="${imgUrl}" class="w-full h-full object-cover"></div>
                <h2 class="text-lg font-bold text-gray-900 leading-tight">${point.nama_bengkel}</h2>
                <div class="flex items-center gap-2 mb-3 mt-1"><div class="flex text-[10px]">${stars}</div><span class="text-xs text-gray-500">(${count})</span> ${statusBadge}</div>
                <div class="text-sm text-gray-600 bg-white p-3 rounded-lg border border-gray-100 space-y-1 mb-3">
                    <p class="flex gap-2 text-xs"><i class="fa-solid fa-map-pin text-red-500"></i> ${point.alamat}</p>
                    <p class="flex gap-2 text-xs"><i class="fa-solid fa-phone text-green-500"></i> ${point.nomer_telepon}</p>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <a href="https://wa.me/${formatWA(point.nomer_telepon)}" target="_blank" class="bg-green-500 text-white py-2 rounded-lg text-xs font-bold text-center flex items-center justify-center gap-1"><i class="fa-brands fa-whatsapp"></i> Chat</a>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${point.latitude},${point.longitude}" target="_blank" class="bg-blue-600 text-white py-2 rounded-lg text-xs font-bold text-center flex items-center justify-center gap-1"><i class="fa-solid fa-diamond-turn-right"></i> Rute</a>
                    <a href="{{ url('/booking') }}/${point.id}" class="col-span-2 bg-orange-500 text-white py-2.5 rounded-lg text-sm font-bold text-center shadow-md"><i class="fa-solid fa-cart-shopping"></i> PESAN SEKARANG</a>
                </div>
                <div class="border-t pt-2"><h3 class="font-bold text-sm text-gray-800 mb-2">Ulasan</h3><div class="max-h-40 overflow-y-auto">${reviewsHtml}</div>${reviewForm}</div>
            `;
        }

        function createMarker(point) {
            var marker = L.marker([point.latitude, point.longitude]).addTo(map);
            marker.bindPopup(`<div class="text-center p-1"><h3 class="font-bold text-sm">${point.nama_bengkel}</h3><button onclick="showDetailSidebar(locations.find(l=>l.id==${point.id})); document.getElementById('rightSidebar').classList.remove('translate-x-full');" class="bg-blue-600 text-white text-[10px] px-2 py-1 rounded mt-1">Lihat Detail</button></div>`);
            marker.on('click', () => { showDetailSidebar(point); document.getElementById('rightSidebar').classList.remove('translate-x-full'); });
            markers.push({marker:marker});
        }
        locations.forEach(p => createMarker(p));

        function renderSidebar(data) {
            currentList = data;
            var container = document.getElementById('sidebarContent'); container.innerHTML = '';
            document.getElementById('resultCount').innerText = data.length;
            if(data.length === 0) { container.innerHTML = '<p class="text-center text-xs text-gray-400 mt-5">Tidak ditemukan</p>'; return; }
            data.forEach((item, i) => {
                var distDisplay = item.distance ? (item.distance < 1 ? Math.round(item.distance*1000)+"m" : item.distance.toFixed(1)+"km") : '';
                var div = document.createElement('div');
                div.className = "bg-white p-3 rounded-xl shadow-sm border border-gray-100 cursor-pointer hover:border-blue-400 relative";
                div.onclick = () => { map.flyTo([item.latitude, item.longitude], 17); showDetailSidebar(item); };
                div.innerHTML = `
                    <div class="flex gap-3">
                        <div class="bg-blue-50 text-blue-600 font-bold w-6 h-6 rounded flex items-center justify-center text-xs">${i+1}</div>
                        <div><h4 class="font-bold text-sm text-gray-800">${item.nama_bengkel}</h4><p class="text-[10px] text-gray-500 line-clamp-1">${item.alamat}</p></div>
                    </div>
                    ${item.distance ? `<span class="absolute top-3 right-3 text-[10px] text-orange-600 font-bold bg-orange-50 px-1 rounded">${distDisplay}</span>` : ''}
                `;
                container.appendChild(div);
            });
        }

        function findNearest() {
            if (!navigator.geolocation) { Swal.fire('Error', 'Browser error', 'error'); return; }
            Swal.showLoading();
            navigator.geolocation.getCurrentPosition(pos => {
                Swal.close();
                var lat = pos.coords.latitude, lng = pos.coords.longitude;
                if(userMarker) map.removeLayer(userMarker);
                userMarker = L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Anda").openPopup();
                
                var results = locations.map(l => ({...l, distance: getDist(lat, lng, l.latitude, l.longitude)})).sort((a,b)=>a.distance-b.distance);
                renderSidebar(results);
                document.getElementById('rightSidebar').classList.remove('translate-x-full');
                if(results.length > 0) map.fitBounds([[lat, lng], [results[0].latitude, results[0].longitude]], {padding:[50,50]});
            }, () => Swal.fire('Error','GPS Mati','error'));
        }

        function getDist(lat1, lon1, lat2, lon2) {
            var R = 6371, dLat = (lat2-lat1)*Math.PI/180, dLon = (lon2-lon1)*Math.PI/180;
            var a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        }

        var sidebar = document.getElementById('rightSidebar');
        function toggleSidebar() { sidebar.classList.contains('translate-x-full') ? sidebar.classList.remove('translate-x-full') : sidebar.classList.add('translate-x-full'); }

        document.getElementById('searchInput').addEventListener('input', (e) => {
            var val = e.target.value.toLowerCase();
            if(['dekat','lokasi'].some(k=>val.includes(k))) { findNearest(); return; }
            var res = locations.filter(l => l.nama_bengkel.toLowerCase().includes(val));
            renderSidebar(res);
            if(val) sidebar.classList.remove('translate-x-full');
        });
        
        renderSidebar(locations);
    </script>
</body>
</html>