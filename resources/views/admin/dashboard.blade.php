<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Dashboard GIS
                </h2>
                <p class="text-sm text-gray-500">Kelola data lokasi, pengguna, dan pantau statistik.</p>
            </div>
            
            <a href="{{ route('tambal-ban.create') }}" class="w-full md:w-auto inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Tambah Lokasi
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Bengkel</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">{{ $data->total() }}</p>
                    </div>
                    <div class="h-10 w-10 md:h-12 md:w-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 shrink-0">
                        <i class="fa-solid fa-location-dot text-lg md:text-xl"></i>
                    </div>
                </div>

                <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status Admin</p>
                        <p class="text-lg md:text-xl font-bold text-green-600 mt-1 flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            Aktif
                        </p>
                    </div>
                    <div class="h-10 w-10 md:h-12 md:w-12 bg-green-50 rounded-full flex items-center justify-center text-green-600 shrink-0">
                        <i class="fa-solid fa-user-shield text-lg md:text-xl"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-5 md:p-6 rounded-xl shadow-md text-white flex flex-col justify-center relative overflow-hidden sm:col-span-2 md:col-span-1">
                    <i class="fa-solid fa-map-location-dot absolute -right-6 -bottom-6 text-7xl md:text-8xl text-white/10 rotate-12"></i>
                    <h3 class="font-bold text-base md:text-lg relative z-10">Kelola Data</h3>
                    <p class="text-blue-100 text-xs md:text-sm mt-1 mb-3 relative z-10">Pastikan data lokasi selalu akurat.</p>
                    <a href="{{ route('tambal-ban.create') }}" class="text-xs md:text-sm font-bold bg-white/20 hover:bg-white/30 py-2 px-3 rounded-lg w-fit transition backdrop-blur-sm relative z-10 border border-white/20">
                        Input Data Baru &rarr;
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-800 text-lg">
                            <i class="fa-solid fa-chart-line text-blue-500 mr-2"></i>Statistik Pengunjung
                        </h3>
                        <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-100">7 Hari Terakhir</span>
                    </div>
                    <div class="relative h-72 w-full">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-800">Pengguna Baru</h3>
                        <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full font-bold">
                            {{ $users->total() }} Total
                        </span>
                    </div>
                    <div class="flex-1 overflow-y-auto max-h-[300px] p-2">
                        @forelse($users as $user)
                        <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition">
                            <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm mr-3 shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="ml-2">
                                @if($user->google_id)
                                    <i class="fa-brands fa-google text-red-500 text-lg" title="Login Google"></i>
                                @else
                                    <i class="fa-solid fa-envelope text-gray-400 text-lg" title="Login Email"></i>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400 text-sm">
                            Belum ada user mendaftar.
                        </div>
                        @endforelse
                    </div>
                    <div class="p-3 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                        {{ $users->links() }} 
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                
                <div class="px-4 md:px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between bg-gray-50/50 gap-4">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-list text-gray-400"></i> Daftar Lokasi
                        </h3>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-bold border border-blue-200">
                            {{ $data->total() }} Data
                        </span>
                    </div>

                    <form method="GET" class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari nama, alamat..." 
                               class="block w-full pl-9 pr-8 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 transition">
                        @if(request('search'))
                            <a href="{{ url()->current() }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 md:px-6 py-3 w-[35%]">Info Bengkel</th>
                                <th class="px-4 md:px-6 py-3 w-[25%]">Operasional</th>
                                <th class="px-4 md:px-6 py-3 w-[25%]">Lokasi & Kontak</th>
                                <th class="px-4 md:px-6 py-3 w-[15%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($data as $item)
                            <tr class="hover:bg-blue-50/30 transition group">
                                
                                <td class="px-4 md:px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-14 w-14 rounded-lg overflow-hidden border border-gray-200 shrink-0 relative bg-gray-100">
                                            @if($item->gambar)
                                                <img src="{{ asset('storage/'.$item->gambar) }}" class="w-full h-full object-cover" alt="Foto">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i class="fa-solid fa-image text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm group-hover:text-blue-600 transition line-clamp-1">{{ $item->nama_bengkel }}</p>
                                            
                                            <div class="flex gap-1 mt-1.5">
                                                @if($item->kategori == 'mobil')
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                                        <i class="fa-solid fa-car"></i> Mobil
                                                    </span>
                                                @elseif($item->kategori == 'motor')
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                                        <i class="fa-solid fa-motorcycle"></i> Motor
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                                        <i class="fa-solid fa-screwdriver-wrench"></i> Umum
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 md:px-6 py-4">
                                    @php
                                        $now = date('H:i');
                                        $buka = substr($item->jam_buka, 0, 5);
                                        $tutup = substr($item->jam_tutup, 0, 5);
                                        $isOpen = $buka <= $now && $tutup >= $now;
                                    @endphp

                                    <div class="flex flex-col items-start gap-1">
                                        <div class="flex items-center gap-2 text-xs text-gray-600 font-mono bg-gray-100 px-2 py-1 rounded border border-gray-200">
                                            <i class="fa-regular fa-clock"></i> {{ $buka }} - {{ $tutup }}
                                        </div>

                                        @if($isOpen)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <span class="relative flex h-2 w-2">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </span>
                                                BUKA SEKARANG
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">
                                                <i class="fa-solid fa-store-slash"></i> TUTUP
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 md:px-6 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        <p class="text-sm text-gray-600 truncate max-w-[220px] flex items-start gap-1.5" title="{{ $item->alamat }}">
                                            <i class="fa-solid fa-map-pin text-gray-400 mt-0.5 text-xs"></i> 
                                            <span>{{ Str::limit($item->alamat, 45) ?? '-' }}</span>
                                        </p>
                                        
                                        @if($item->nomer_telepon)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $item->nomer_telepon)) }}" target="_blank" class="text-xs text-green-600 font-bold flex items-center gap-1 hover:underline w-fit">
                                            <i class="fa-brands fa-whatsapp text-lg"></i> {{ $item->nomer_telepon }}
                                        </a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 md:px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('tambal-ban.edit', $item->id) }}" class="h-8 w-8 rounded-lg flex items-center justify-center bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition border border-yellow-200 shadow-sm" title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        
                                        <form onsubmit="return confirm('Yakin ingin menghapus data {{ $item->nama_bengkel }}?');" action="{{ route('tambal-ban.destroy', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="h-8 w-8 rounded-lg flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition border border-red-200 shadow-sm" title="Hapus Permanen">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 bg-gray-50/50">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-white p-4 rounded-full shadow-sm mb-3">
                                            <i class="fa-solid fa-magnifying-glass text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-medium text-gray-500">Data tidak ditemukan.</p>
                                        <p class="text-xs text-gray-400 mt-1">Coba kata kunci pencarian lain.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 text-xs text-gray-500">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            Menampilkan {{ $data->firstItem() ?? 0 }} sampai {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }} data.
                        </div>
                        <div class="mt-2 sm:mt-0">
                            {{ $data->links() }} 
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('visitorChart');
            if (ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels ?? []) !!}, 
                        datasets: [{
                            label: 'Jumlah Pengunjung',
                            data: {!! json_encode($chartData ?? []) !!},
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#3B82F6',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1 },
                                grid: { color: '#f3f4f6' }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>