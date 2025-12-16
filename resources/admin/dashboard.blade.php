<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Dashboard GIS
                </h2>
                <p class="text-sm text-gray-500">Kelola data lokasi dan pantau sebaran.</p>
            </div>
            <div class="flex gap-3">
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Bengkel</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $data->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-location-dot text-xl"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status Admin</p>
                        <p class="text-xl font-bold text-green-600 mt-1">Aktif</p>
                    </div>
                    <div class="h-12 w-12 bg-green-50 rounded-full flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-user-shield text-xl"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-6 rounded-xl shadow-md text-white flex flex-col justify-center relative overflow-hidden">
                    <i class="fa-solid fa-map-location-dot absolute -right-4 -bottom-4 text-8xl text-white/10"></i>
                    <h3 class="font-bold text-lg">Butuh Update?</h3>
                    <p class="text-blue-100 text-sm mt-1 mb-3">Pastikan data lokasi selalu akurat.</p>
                    <a href="{{ route('tambal-ban.create') }}" class="text-sm font-bold underline hover:text-blue-200 w-fit">Input Data Baru &rarr;</a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Daftar Lokasi Terdaftar</h3>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-bold">{{ $data->count() }} Items</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-6 py-3">Nama Bengkel</th>
                                <th class="px-6 py-3">Alamat & Kontak</th>
                                <th class="px-6 py-3">Koordinat</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($data as $item)
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold mr-3 text-sm">
                                            {{ substr($item->nama_bengkel, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm">{{ $item->nama_bengkel }}</p>
                                            <p class="text-xs text-gray-400">ID: #{{ $item->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 truncate max-w-xs" title="{{ $item->alamat }}">{{ Str::limit($item->alamat, 40) ?? '-' }}</p>
                                    <p class="text-xs text-blue-600 font-medium mt-1">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $item->nomer_telepon ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-gray-100 text-gray-600 text-xs font-mono px-2 py-1 rounded border border-gray-200">
                                        {{ number_format($item->latitude, 4) }}, {{ number_format($item->longitude, 4) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form onsubmit="return confirm('Hapus data ini?');" action="{{ route('tambal-ban.destroy', $item->id) }}" method="POST" class="inline-flex gap-2">
                                        <a href="{{ route('tambal-ban.edit', $item->id) }}" class="h-8 w-8 rounded flex items-center justify-center bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition border border-yellow-200" title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        @csrf @method('DELETE')
                                        <button type="submit" class="h-8 w-8 rounded flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 transition border border-red-200" title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                    <i class="fa-regular fa-folder-open text-4xl mb-3 block opacity-30"></i>
                                    Belum ada data lokasi.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
