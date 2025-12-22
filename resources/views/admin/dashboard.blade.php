<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Dashboard GIS
                </h2>
                <p class="text-sm text-gray-500">Kelola data lokasi dan pantau sebaran.</p>
            </div>
            
            <a href="{{ route('tambal-ban.create') }}" class="w-full md:w-auto inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Tambah Lokasi
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                
                <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Bengkel</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">{{ $data->count() }}</p>
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
                    <p class="text-blue-100 text-xs md:text-sm mt-1 mb-3 relative z-10">Pastikan data lokasi selalu akurat untuk pengguna.</p>
                    <a href="{{ route('tambal-ban.create') }}" class="text-xs md:text-sm font-bold bg-white/20 hover:bg-white/30 py-2 px-3 rounded-lg w-fit transition backdrop-blur-sm relative z-10 border border-white/20">
                        Input Data Baru &rarr;
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 md:px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between bg-gray-50/50 gap-2">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-list text-gray-400"></i> Daftar Lokasi
                    </h3>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-bold border border-blue-200">
                        {{ $data->count() }} Data
                    </span>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[800px]"> <thead>
                            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 md:px-6 py-3 w-1/3">Nama Bengkel</th>
                                <th class="px-4 md:px-6 py-3 w-1/3">Alamat & Kontak</th>
                                <th class="px-4 md:px-6 py-3">Koordinat</th>
                                <th class="px-4 md:px-6 py-3 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($data as $item)
                            <tr class="hover:bg-blue-50/30 transition group">
                                <td class="px-4 md:px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 font-bold mr-3 text-sm shrink-0 border border-blue-100">
                                            {{ substr($item->nama_bengkel, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm group-hover:text-blue-600 transition">{{ $item->nama_bengkel }}</p>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-mono">ID: #{{ $item->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    <p class="text-sm text-gray-600 truncate max-w-[200px]" title="{{ $item->alamat }}">{{ Str::limit($item->alamat, 40) ?? '-' }}</p>
                                    <p class="text-xs text-green-600 font-bold mt-1 flex items-center gap-1 bg-green-50 w-fit px-2 py-0.5 rounded border border-green-100">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $item->nomer_telepon ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="bg-gray-100 text-gray-600 text-[10px] font-mono px-2 py-1 rounded border border-gray-200 w-fit">
                                            Lat: {{ number_format($item->latitude, 5) }}
                                        </span>
                                        <span class="bg-gray-100 text-gray-600 text-[10px] font-mono px-2 py-1 rounded border border-gray-200 w-fit">
                                            Lng: {{ number_format($item->longitude, 5) }}
                                        </span>
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
                                            <i class="fa-regular fa-folder-open text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-medium text-gray-500">Belum ada data lokasi.</p>
                                        <p class="text-xs text-gray-400 mt-1">Silakan tambahkan data baru.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 text-xs text-gray-500 text-center md:text-left">
                    Menampilkan seluruh data lokasi.
                </div>
            </div>

        </div>
    </div>
</x-app-layout>