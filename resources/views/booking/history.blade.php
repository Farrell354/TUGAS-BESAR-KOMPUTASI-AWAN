<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('peta.index') }}" class="bg-white border border-gray-300 h-10 w-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                Riwayat Pesanan
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($orders->isEmpty())
                <div class="bg-white p-12 rounded-2xl text-center shadow-sm border border-gray-100">
                    <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-regular fa-folder-open text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada riwayat</h3>
                    <p class="text-gray-500 mb-8 text-sm max-w-md mx-auto">Anda belum pernah melakukan pemesanan jasa tambal ban. Pesanan aktif dan selesai akan muncul di sini.</p>
                    <a href="{{ route('peta.index') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 transform hover:-translate-y-1">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari Bengkel Sekarang
                    </a>
                </div>
            @else

            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-200 relative group overflow-hidden">

                        @php
                            $statusColor = match($order->status) {
                                'pending' => 'bg-yellow-400',
                                'proses' => 'bg-blue-500',
                                'selesai' => 'bg-green-500',
                                'batal' => 'bg-red-500',
                                default => 'bg-gray-300'
                            };
                            $statusLabel = match($order->status) {
                                'pending' => 'Menunggu Konfirmasi',
                                'proses' => 'Sedang Jalan (OTW)',
                                'selesai' => 'Selesai',
                                'batal' => 'Dibatalkan',
                                default => ucfirst($order->status)
                            };
                            $badgeColor = match($order->status) {
                                'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'selesai' => 'bg-green-50 text-green-700 border-green-200',
                                'batal' => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-gray-50 text-gray-700 border-gray-200'
                            };
                        @endphp
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $statusColor }}"></div>

                        <div class="flex flex-col md:flex-row justify-between gap-4 pl-3">

                            <div class="flex-1 cursor-pointer" onclick="window.location='{{ route('booking.show', $order->id) }}'">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-blue-600 transition leading-tight">
                                        {{ $order->tambalBan->nama_bengkel }}
                                    </h3>
                                    <span class="text-[10px] px-2 py-0.5 rounded border font-bold uppercase {{ $badgeColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-500 mb-2 flex items-start gap-1.5 leading-snug">
                                    <i class="fa-solid fa-map-pin mt-0.5 text-red-400 shrink-0"></i>
                                    <span class="line-clamp-1">{{ $order->alamat_lengkap }}</span>
                                </p>
                                
                                <div class="flex items-center gap-3 text-xs text-gray-400 font-medium">
                                    <span class="flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i> {{ $order->created_at->format('d M Y, H:i') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fa-solid {{ $order->jenis_kendaraan == 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i> {{ ucfirst($order->jenis_kendaraan) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-row md:flex-col items-center md:items-end justify-end gap-2 mt-2 md:mt-0 pt-3 md:pt-0 border-t md:border-t-0 border-gray-100">

                                <a href="{{ route('booking.show', $order->id) }}" class="flex-1 md:flex-none text-center bg-gray-50 hover:bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-xs font-bold transition border border-gray-200">
                                    Detail
                                </a>

                                @if($order->status == 'pending')
                                    <form id="cancelForm-{{ $order->id }}" action="{{ route('booking.cancel', $order->id) }}" method="POST" class="flex-1 md:flex-none w-full">
                                        @csrf @method('PATCH')
                                        <button type="button" onclick="confirmCancel({{ $order->id }})" class="w-full bg-white border border-red-200 text-red-600 hover:bg-red-50 text-xs px-4 py-2 rounded-lg font-bold transition flex items-center justify-center gap-1 shadow-sm whitespace-nowrap">
                                            <i class="fa-solid fa-ban"></i> Batal
                                        </button>
                                    </form>
                                @endif

                                @if($order->status == 'proses')
                                    <a href="{{ route('chat.show', $order->id) }}" class="flex-1 md:flex-none text-center bg-blue-600 hover:bg-blue-700 text-white text-xs px-4 py-2 rounded-lg font-bold transition flex items-center justify-center gap-2 shadow-md w-full whitespace-nowrap animate-pulse">
                                        <i class="fa-regular fa-comments text-sm"></i> Chat
                                    </a>
                                @endif

                                @php
                                    $waBengkel = $order->tambalBan->nomer_telepon;
                                    if(substr($waBengkel, 0, 1) == '0') $waBengkel = '62' . substr($waBengkel, 1);
                                @endphp
                                <a href="https://wa.me/{{ $waBengkel }}" target="_blank" class="flex-1 md:flex-none text-center bg-green-50 text-green-600 border border-green-200 hover:bg-green-100 text-xs px-4 py-2 rounded-lg font-bold transition flex items-center justify-center gap-1 w-full whitespace-nowrap">
                                    <i class="fa-brands fa-whatsapp text-sm"></i> WA
                                </a>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmCancel(orderId) {
            Swal.fire({
                title: 'Batalkan Pesanan?',
                text: "Apakah Anda yakin ingin membatalkan pesanan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl shadow-xl border border-gray-100',
                    confirmButton: 'px-4 py-2 rounded-lg font-bold text-sm',
                    cancelButton: 'px-4 py-2 rounded-lg font-bold text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancelForm-' + orderId).submit();
                }
            });
        }
    </script>
</x-app-layout>