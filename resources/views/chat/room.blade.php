<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold border-2 border-white shadow-sm">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h2 class="font-bold text-lg text-gray-800 leading-tight">
                        @if(Auth::id() == $order->user_id)
                            {{ $order->tambalBan->nama_bengkel }}
                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full ml-1">Owner</span>
                        @else
                            {{ $order->nama_pemesan }}
                            <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full ml-1">Pelanggan</span>
                        @endif
                    </h2>
                    <p class="text-xs text-green-500 flex items-center gap-1">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        Online
                    </p>
                </div>
            </div>

            <a href="{{ Auth::user()->role == 'owner' ? route('owner.dashboard') : route('booking.history') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 h-9 w-9 rounded-full flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-4 h-[calc(100vh-140px)] flex flex-col relative">

        <div id="chatBox" class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar rounded-t-2xl shadow-inner border border-gray-200 relative bg-[#e5ddd5]">

            <div class="absolute inset-0 opacity-10 pointer-events-none"
                 style="background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-repeat: repeat;">
            </div>

            <div id="chatContent" class="relative z-10 space-y-3">
                <div class="text-center text-gray-500 py-10">
                    <i class="fa-solid fa-circle-notch fa-spin"></i> Memuat percakapan...
                </div>
            </div>

        </div>

        <div class="bg-white p-3 rounded-b-2xl shadow-lg border-x border-b border-gray-200 z-20">
            <div class="flex gap-2 items-end">
                <textarea id="messageInput" rows="1"
                    class="flex-1 bg-gray-100 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 text-gray-800 placeholder-gray-500 resize-none custom-scrollbar"
                    placeholder="Ketik pesan..." style="min-height: 44px; max-height: 120px;"></textarea>

                <button onclick="sendMessage()"
                    class="bg-blue-600 hover:bg-blue-700 text-white w-11 h-11 rounded-full flex items-center justify-center shadow-md transition transform active:scale-90 flex-shrink-0 mb-0.5">
                    <i class="fa-solid fa-paper-plane text-sm pl-0.5"></i>
                </button>
            </div>
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* Bubble Styles */
        .bubble { max-width: 80%; padding: 8px 12px; position: relative; font-size: 0.95rem; line-height: 1.4; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }

        /* Bubble Saya (Kanan) */
        .bubble-me {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border-radius: 16px 16px 0 16px;
            margin-left: auto;
        }

        /* Bubble Lawan (Kiri) */
        .bubble-other {
            background: white;
            color: #1f2937;
            border-radius: 16px 16px 16px 0;
            margin-right: auto;
        }

        .chat-time { font-size: 0.65rem; margin-top: 2px; display: block; text-align: right; opacity: 0.7; }
    </style>

    <script>
        const orderId = {{ $order->id }};
        const currentUserId = {{ Auth::id() }};
        const chatBox = document.getElementById('chatBox');
        const chatContent = document.getElementById('chatContent');
        const messageInput = document.getElementById('messageInput');
        let isFirstLoad = true;

        // Auto Resize Textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // 1. Fungsi Kirim Pesan
        function sendMessage() {
            let msg = messageInput.value.trim();
            if(!msg) return;

            // Optimistic UI (Tampil langsung)
            appendMessage({
                message: msg,
                sender_id: currentUserId,
                created_at: new Date().toISOString()
            });
            scrollToBottom();

            messageInput.value = '';
            messageInput.style.height = '44px'; // Reset tinggi

            fetch(`/chat/${orderId}/send`, {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ message: msg })
            }).catch(err => console.error("Gagal kirim", err));
        }

        // 2. Load Pesan
        function loadMessages() {
            fetch(`/chat/${orderId}/get`)
                .then(res => res.json())
                .then(data => {
                    chatContent.innerHTML = '';

                    if(data.length === 0) {
                        chatContent.innerHTML = `
                            <div class="flex flex-col items-center justify-center h-full pt-10 opacity-60">
                                <div class="bg-blue-50 p-4 rounded-full mb-2"><i class="fa-regular fa-comments text-3xl text-blue-400"></i></div>
                                <p class="text-sm text-gray-500 font-medium">Belum ada percakapan.</p>
                                <p class="text-xs text-gray-400">Mulai chat untuk diskusi.</p>
                            </div>`;
                    } else {
                        // Grouping Tanggal (Opsional, di sini kita render flat dulu)
                        data.forEach(chat => appendMessage(chat));
                    }

                    if(isFirstLoad) {
                        scrollToBottom();
                        isFirstLoad = false;
                    }
                });
        }

        function appendMessage(chat) {
            let isMe = chat.sender_id == currentUserId;
            let date = new Date(chat.created_at);
            let time = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

            let bubbleHtml = `
                <div class="flex w-full ${isMe ? 'justify-end' : 'justify-start'} mb-1 animate-fade-in-up">
                    <div class="bubble ${isMe ? 'bubble-me' : 'bubble-other'}">
                        <p class="whitespace-pre-wrap">${chat.message}</p>
                        <div class="flex items-center justify-end gap-1 mt-1">
                            <span class="chat-time ${isMe ? 'text-blue-100' : 'text-gray-400'}">${time}</span>
                            ${isMe ? '<i class="fa-solid fa-check text-[10px] text-blue-200"></i>' : ''}
                        </div>
                    </div>
                </div>
            `;
            chatContent.insertAdjacentHTML('beforeend', bubbleHtml);
        }

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        setInterval(loadMessages, 3000); // Refresh tiap 3 detik
        loadMessages();

        messageInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter" && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        });
    </script>
</x-app-layout>
