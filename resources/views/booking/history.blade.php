<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Riwayat Pesanan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if($orders->isEmpty())
                <div class="bg-white p-12 rounded-xl text-center shadow-sm border border-gray-100">
                    <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-regular fa-folder-open text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">Belum ada riwayat</h3>
                    <p class="text-gray-500 mb-6 text-sm">Anda belum pernah melakukan pemesanan jasa.</p>
                    <a href="{{ route('peta.index') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-blue-700 transition shadow-lg transform hover:-translate-y-1">
                        Cari Bengkel Sekarang
                    </a>
                </div>
            @else

            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-200 relative group overflow-hidden">

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
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'proses' => 'bg-blue-100 text-blue-700',
                                'selesai' => 'bg-green-100 text-green-700',
                                'batal' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $statusColor }}"></div>

                        <div class="flex flex-col md:flex-row justify-between gap-4 pl-3">

                            <div class="flex-1 cursor-pointer" onclick="window.location='{{ route('booking.show', $order->id) }}'">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-600 transition">
                                        {{ $order->tambalBan->nama_bengkel }}
                                    </h3>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase {{ $badgeColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-500 mb-1 flex items-start gap-1">
                                    <i class="fa-solid fa-map-pin mt-1 text-red-400"></i>
                                    <span class="line-clamp-1">{{ $order->alamat_lengkap }}</span>
                                </p>
                                <p class="text-xs text-gray-400 flex items-center gap-1">
                                    <i class="fa-regular fa-clock"></i> {{ $order->created_at->format('d M Y, H:i') }} WIB
                                </p>
                            </div>

                            <div class="flex flex-col md:items-end justify-center gap-2">

                                <a href="{{ route('booking.show', $order->id) }}" class="text-xs font-bold text-gray-500 hover:text-blue-600 flex items-center gap-1 md:hidden">
                                    Lihat Detail <i class="fa-solid fa-arrow-right"></i>
                                </a>

                               @if($order->status == 'pending')
                                    <form id="cancelForm-{{ $order->id }}" action="{{ route('booking.cancel', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="button" onclick="confirmCancel({{ $order->id }})" class="bg-white border border-red-200 text-red-600 hover:bg-red-50 text-xs px-4 py-2 rounded-lg font-bold transition flex items-center gap-1 shadow-sm">
                                            <i class="fa-solid fa-ban"></i> Batalkan
                                        </button>
                                    </form>
                                @endif

                                @if($order->status == 'proses')
                                    <a href="{{ route('chat.show', $order->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-4 py-2 rounded-lg font-bold transition flex items-center gap-2 shadow-md">
                                        <i class="fa-regular fa-comments text-base"></i> Chat Owner
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-red-400"></span>
                                        </span>
                                    </a>
                                @endif

                                @php
                                    $waBengkel = $order->tambalBan->nomer_telepon;
                                    if(substr($waBengkel, 0, 1) == '0') $waBengkel = '62' . substr($waBengkel, 1);
                                @endphp
                                <a href="https://wa.me/{{ $waBengkel }}" target="_blank" class="bg-green-50 text-green-600 border border-green-200 hover:bg-green-100 text-xs px-4 py-2 rounded-lg font-bold transition flex items-center gap-1">
                                    <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                                </a>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>

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
                background: '#fff',
                customClass: {
                    popup: 'rounded-xl shadow-xl border border-gray-100',
                    confirmButton: 'px-4 py-2 rounded-lg font-bold text-sm',
                    cancelButton: 'px-4 py-2 rounded-lg font-bold text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form yang sesuai dengan ID pesanan
                    document.getElementById('cancelForm-' + orderId).submit();
                }
            });
        }
    </script>
</x-app-layout>
