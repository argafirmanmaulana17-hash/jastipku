@extends('layouts.app')
@section('title', 'Ruang Obrolan - JastipKu')

@section('content')
    <div class="pt-24 pb-20 bg-slate-50 min-h-screen flex flex-col">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1 flex flex-col">

            <div class="bg-white rounded-t-3xl border border-slate-200 p-4 flex items-center justify-between shadow-sm z-10">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xl">
                            {{ substr($lawanBicara, 0, 1) }}
                        </div>
                        <span
                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <h1 class="font-display font-bold text-slate-800 text-lg">{{ $lawanBicara }}</h1>
                        <p class="text-xs text-slate-500 font-semibold">Order: {{ $order->kode_order }}</p>
                    </div>
                </div>
                <a href="javascript:history.back()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            <div id="chat-box"
                class="bg-slate-100/50 border-x border-slate-200 flex-1 p-6 overflow-y-auto min-h-[400px] flex flex-col gap-4">

                <div class="flex justify-center mb-2">
                    <span class="text-xs bg-slate-200 text-slate-500 px-3 py-1 rounded-full font-medium">Ruang obrolan aman
                        & terenkripsi</span>
                </div>

                @forelse($chats as $chat)
                    @if ($chat->sender_id === Auth::id())
                        <div class="flex justify-end">
                            <div
                                class="bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none shadow-sm shadow-blue-200 max-w-[80%]">
                                <p class="text-sm">{{ $chat->message }}</p>
                                <span
                                    class="text-[10px] text-blue-200 mt-2 block text-right">{{ $chat->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-start">
                            <div
                                class="bg-white border border-slate-200 text-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                                <p class="text-sm">{{ $chat->message }}</p>
                                <span
                                    class="text-[10px] text-slate-400 mt-2 block text-right">{{ $chat->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center text-slate-400 text-sm my-auto">
                        Belum ada obrolan. Sapa {{ $lawanBicara }} sekarang!
                    </div>
                @endforelse

            </div>

            <div class="bg-white rounded-b-3xl border border-slate-200 p-4 shadow-sm">
                <form id="chat-form" action="{{ route('chat.store', $order->id) }}" method="POST"
                    class="flex gap-3 items-end">
                    @csrf
                    <textarea id="message-input" name="message" rows="1"
                        class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 resize-none"
                        placeholder="Ketik pesan atau lampirkan link bukti bayar..." required></textarea>
                    <button type="submit"
                        class="bg-blue-600 text-white p-3 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                        <svg class="w-6 h-6 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');

        // Simpan ID pesan terakhir
        let lastChatId = {{ $chats->count() > 0 ? $chats->last()->id : 0 }};
        const orderId = {{ $order->id }};

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Gulir ke bawah saat pertama buka
        scrollToBottom();

        // 1. Fungsi Tarik Pesan Otomatis (Setiap 3 Detik)
        setInterval(function() {
            fetch(`/chat/${orderId}/messages?last_id=${lastChatId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(chat => {
                            lastChatId = chat.id; // Update ID terakhir

                            let html = '';
                            if (chat.is_me) {
                                html = `
                            <div class="flex justify-end">
                                <div class="bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none shadow-sm max-w-[80%]">
                                    <p class="text-sm">${chat.message}</p>
                                    <span class="text-[10px] text-blue-200 mt-2 block text-right">${chat.time}</span>
                                </div>
                            </div>`;
                            } else {
                                html = `
                            <div class="flex justify-start">
                                <div class="bg-white border border-slate-200 text-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                                    <p class="text-sm">${chat.message}</p>
                                    <span class="text-[10px] text-slate-400 mt-2 block text-right">${chat.time}</span>
                                </div>
                            </div>`;
                            }
                            chatBox.insertAdjacentHTML('beforeend', html);
                        });
                        scrollToBottom();
                    }
                });
        }, 3000);

        // 2. Kirim Pesan Tanpa Refresh Loading
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah halaman refresh
            let msg = messageInput.value;
            if (!msg) return;

            messageInput.value = ''; // Kosongkan ketikan

            fetch(chatForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: msg
                })
            });
            // Kita tidak perlu menggambar manual, karena interval 3 detik di atas 
            // akan langsung menarik pesan yang baru saja kita simpan ini.
        });
    </script>
@endsection
