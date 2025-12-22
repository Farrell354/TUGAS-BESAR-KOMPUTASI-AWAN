<x-app-layout>
    <div class="flex flex-col h-[calc(100dvh-65px)] bg-[#efe7dd]"> 
        <div class="bg-white px-4 py-3 shadow-sm border-b border-gray-200 flex justify-between items-center z-20 shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold border border-gray-200 overflow-hidden">
                        <i class="fa-solid fa-user text-lg"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-500 border-2 border-white"></span>
                </div>
                
                <div class="leading-tight">
                    <h2 class="font-bold text-gray-800 text-sm md:text-base line-clamp-1">
                        @if(Auth::id() == $order->user_id)
                            {{ $order->tambalBan->nama_bengkel }}
                            <span class="bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.5 rounded font-bold ml-1 align-middle">MITRA</span>
                        @else
                            {{ $order->nama_pemesan }}
                            <span class="bg-green-100 text-green-700 text-[10px] px-1.5 py-0.5 rounded font-bold ml-1 align-middle">USER</span>
                        @endif
                    </h2>
                    <p class="text-[10px] md:text-xs text-green-600 font-medium">● Online</p>
                </div>
            </div>

            <a href="{{ Auth::user()->role == 'owner' ? route('owner.dashboard') : route('booking.history') }}"
               class="h-9 w-9 flex items-center justify-center rounded-full bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-red-500 transition border border-gray-200 shadow-sm">
                <i class="fa-solid fa-xmark text-lg"></i>
            </a>
        </div>

        <div id="chatBox" class="flex-1 overflow-y-auto p-4 space-y-2 relative scroll-smooth">
            
            <div class="absolute inset-0 opacity-40 pointer-events-none"
                 style="background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-repeat: repeat;">
            </div>

            <div id="chatContent" class="relative z-10 flex flex-col gap-2 pb-2">
                <div class="flex flex-col items-center justify-center py-8 text-gray-400 opacity-0 transition-opacity duration-500" id="loadingState">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2"></i>
                    <span class="text-xs">Memuat percakapan...</span>
                </div>
            </div>
        </div>

        <div class="bg-[#f0f2f5] px-2 py-2 md:px-4 md:py-3 shrink-0 z-20 border-t border-gray-300">
            <div class="max-w-4xl mx-auto flex items-end gap-2">
                <div class="flex-1 bg-white rounded-2xl flex items-center shadow-sm border border-gray-300 overflow-hidden px-1">
                    <textarea id="messageInput" rows="1"
                        class="w-full border-none focus:ring-0 text-sm text-gray-800 placeholder-gray-500 py-3 px-3 resize-none max-h-32"
                        placeholder="Ketik pesan..." style="min-height: 44px;"></textarea>
                </div>

                <button onclick="sendMessage()"
                    class="bg-blue-600 hover:bg-blue-700 text-white w-11 h-11 rounded-full flex items-center justify-center shadow-md transition transform active:scale-95 shrink-0 mb-0.5">
                    <i class="fa-solid fa-paper-plane text-sm ml-0.5"></i>
                </button>
            </div>
        </div>

    </div>

    <style>
        /* Custom Scrollbar */
        #chatBox::-webkit-scrollbar { width: 4px; }
        #chatBox::-webkit-scrollbar-track { background: transparent; }
        #chatBox::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* Bubble Chat */
        .bubble {
            max-width: 85%;
            padding: 8px 12px;
            position: relative;
            font-size: 0.9rem;
            line-height: 1.4;
            box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
            word-wrap: break-word;
        }

        .bubble-me {
            background-color: #d9fdd3;
            color: #111b21;
            border-radius: 8px 0 8px 8px;
            margin-left: auto;
        }
        
        .bubble-other {
            background-color: #ffffff;
            color: #111b21;
            border-radius: 0 8px 8px 8px;
            margin-right: auto;
        }

        /* Ekor Bubble */
        .bubble-me::before {
            content: ""; position: absolute; top: 0; right: -8px; width: 0; height: 0;
            border-top: 8px solid #d9fdd3; border-right: 8px solid transparent;
        }
        .bubble-other::before {
            content: ""; position: absolute; top: 0; left: -8px; width: 0; height: 0;
            border-top: 8px solid #ffffff; border-left: 8px solid transparent;
        }

        .time-stamp {
            font-size: 0.65rem; color: #667781; float: right; margin-left: 8px; margin-top: 4px;
        }
    </style>

    <script>
        const orderId = {{ $order->id }};
        const currentUserId = {{ Auth::id() }};
        const chatContent = document.getElementById('chatContent');
        const chatBox = document.getElementById('chatBox');
        const messageInput = document.getElementById('messageInput');
        const loadingState = document.getElementById('loadingState');
        let isFirstLoad = true;

        // Auto Resize Textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Send Message
        function sendMessage() {
            let msg = messageInput.value.trim();
            if(!msg) return;

            // UI Optimis
            appendMessage({
                message: msg,
                sender_id: currentUserId,
                created_at: new Date().toISOString()
            });
            scrollToBottom();

            messageInput.value = '';
            messageInput.style.height = '44px';
            messageInput.focus();

            fetch(`/chat/${orderId}/send`, {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ message: msg })
            }).catch(err => console.error("Gagal kirim", err));
        }

        // Fetch Messages
        function loadMessages() {
            fetch(`/chat/${orderId}/get`)
                .then(res => res.json())
                .then(data => {
                    chatContent.innerHTML = ''; // Clear content to prevent duplicate (simple approach)
                    
                    if(data.length === 0) {
                        chatContent.innerHTML = `
                            <div class="flex flex-col items-center justify-center mt-20 opacity-60">
                                <div class="bg-white p-4 rounded-full mb-3 shadow-sm"><i class="fa-regular fa-comments text-4xl text-blue-300"></i></div>
                                <p class="text-sm text-gray-500 font-bold bg-white/50 px-3 py-1 rounded-full backdrop-blur-sm">Belum ada pesan</p>
                            </div>`;
                    } else {
                        // Render Date Divider Logic could go here
                        data.forEach(chat => appendMessage(chat));
                    }

                    if(isFirstLoad) {
                        scrollToBottom();
                        loadingState.classList.add('hidden'); // Hide loader
                        loadingState.classList.remove('opacity-0'); // Reset opacity class
                        isFirstLoad = false;
                    }
                });
        }

        function appendMessage(chat) {
            let isMe = chat.sender_id == currentUserId;
            let date = new Date(chat.created_at);
            let time = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

            let html = `
                <div class="flex w-full ${isMe ? 'justify-end' : 'justify-start'} animate-fade-in-up">
                    <div class="bubble ${isMe ? 'bubble-me' : 'bubble-other'}">
                        ${chat.message}
                        <div class="time-stamp">
                            ${time} 
                            ${isMe ? '<i class="fa-solid fa-check text-blue-500 ml-0.5"></i>' : ''}
                        </div>
                    </div>
                </div>
            `;
            chatContent.insertAdjacentHTML('beforeend', html);
        }

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Polling setiap 3 detik
        setInterval(loadMessages, 3000);
        
        // Initial Load
        loadingState.classList.remove('opacity-0'); // Show loader initially
        loadMessages();

        // Enter to Send (Mobile friendly: shift+enter for new line)
        messageInput.addEventListener("keydown", function(e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>
</x-app-layout> 