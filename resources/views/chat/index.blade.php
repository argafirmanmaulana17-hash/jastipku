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
                        <h1 class="font-display font-bold text-slate-800 text-lg">
                            {{ $lawanBicara }}
                        </h1>
                        <p class="text-xs text-slate-500 font-semibold">
                            Order: {{ $order->kode_order }}
                        </p>
                    </div>
                </div>

                <a href="javascript:history.back()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            <div class="bg-white border-x border-slate-200 px-4 py-4">
                @if (session('success'))
                    <div
                        class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-3 mb-4 text-sm font-semibold">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-3 mb-4 text-sm">
                        <div class="font-bold mb-1">Ada yang perlu diperbaiki:</div>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($order->priceOffers && $order->priceOffers->count() > 0)
                    <div class="space-y-3 mb-4">
                        @foreach ($order->priceOffers as $offer)
                            <div
                                class="border rounded-2xl p-4
                                {{ $offer->status == 'accepted' ? 'bg-green-50 border-green-200' : '' }}
                                {{ $offer->status == 'rejected' ? 'bg-slate-50 border-slate-200 opacity-70' : '' }}
                                {{ $offer->status == 'pending' ? 'bg-yellow-50 border-yellow-200' : '' }}">

                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-bold text-sm text-slate-800">
                                        💰 Penawaran Harga
                                    </div>

                                    <div class="text-xs font-semibold">
                                        {{ ucfirst($offer->status) }}
                                    </div>
                                </div>

                                <div class="text-sm text-slate-700 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Harga Barang</span>
                                        <b>Rp {{ number_format($offer->harga_barang, 0, ',', '.') }}</b>
                                    </div>

                                    <div class="flex justify-between">
                                        <span>Ongkos Jastip</span>
                                        <b>Rp {{ number_format($offer->ongkos_jastip, 0, ',', '.') }}</b>
                                    </div>

                                    <div class="flex justify-between border-t border-slate-200 pt-2 mt-2">
                                        <span>Total</span>
                                        <b class="text-blue-700">
                                            Rp {{ number_format($offer->total_bayar, 0, ',', '.') }}
                                        </b>
                                    </div>
                                </div>

                                @if ($offer->catatan)
                                    <div class="text-xs text-slate-500 mt-3">
                                        Catatan: {{ $offer->catatan }}
                                    </div>
                                @endif

                                <div class="text-xs text-slate-400 mt-3">
                                    Diajukan oleh: {{ $offer->sender->name ?? 'User' }}
                                </div>

                                @if ($offer->status == 'pending')
                                    @if ($offer->sender_id !== auth()->id())
                                        <div class="flex gap-2 mt-4">
                                            <form method="POST"
                                                action="{{ route('orders.price-offers.accept', [$order->id, $offer->id]) }}"
                                                class="flex-1">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="w-full bg-blue-600 text-white text-xs font-bold py-2 rounded-xl">
                                                    Setujui
                                                </button>
                                            </form>

                                            <form method="POST"
                                                action="{{ route('orders.price-offers.reject', [$order->id, $offer->id]) }}"
                                                class="flex-1">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="w-full bg-red-50 text-red-600 border border-red-200 text-xs font-bold py-2 rounded-xl">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div
                                            class="mt-4 text-xs text-slate-500 bg-white/70 border border-slate-200 rounded-xl p-3 text-center">
                                            Menunggu respon dari lawan bicara.
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (in_array($order->status, ['menunggu_harga', 'menunggu_persetujuan']))
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-4">
                        <h3 class="font-bold text-slate-900 mb-3">
                            Ajukan / Banding Harga
                        </h3>

                        <form method="POST" action="{{ route('orders.price-offers.store', $order->id) }}"
                            class="space-y-3">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">
                                        Harga Barang
                                    </label>
                                    <input type="number" name="harga_barang"
                                        value="{{ old('harga_barang', $order->harga_barang) }}"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">
                                        Ongkos Jastip
                                    </label>
                                    <input type="number" name="ongkos_jastip"
                                        value="{{ old('ongkos_jastip', $order->ongkos_jastip) }}"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">
                                    Catatan
                                </label>
                                <textarea name="catatan" rows="2" placeholder="Contoh: Kalau bisa Rp16.000 saja ya"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm">{{ old('catatan') }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl text-sm">
                                Kirim Penawaran Harga
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div id="chat-box"
                class="bg-slate-100/50 border-x border-slate-200 flex-1 p-6 overflow-y-auto min-h-[400px] flex flex-col gap-4">

                <div class="flex justify-center mb-2">
                    <span class="text-xs bg-slate-200 text-slate-500 px-3 py-1 rounded-full font-medium">
                        Ruang obrolan aman & terenkripsi
                    </span>
                </div>

                @forelse ($chats as $chat)
                    @if ($chat->sender_id === auth()->id())
                        <div class="flex justify-end">
                            <div
                                class="bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none shadow-sm shadow-blue-200 max-w-[80%]">
                                <p class="text-sm whitespace-pre-line">{{ $chat->message }}</p>
                                <span class="text-[10px] text-blue-200 mt-2 block text-right">
                                    {{ $chat->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-start">
                            <div
                                class="bg-white border border-slate-200 text-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                                <p class="text-sm whitespace-pre-line">{{ $chat->message }}</p>
                                <span class="text-[10px] text-slate-400 mt-2 block text-right">
                                    {{ $chat->created_at->format('H:i') }}
                                </span>
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

        let lastChatId = {{ $chats->count() > 0 ? $chats->last()->id : 0 }};
        const orderId = {{ $order->id }};

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.innerText = text;
            return div.innerHTML;
        }

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        scrollToBottom();

        setInterval(function() {
            fetch(`/chat/${orderId}/messages?last_id=${lastChatId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(chat => {
                            lastChatId = chat.id;

                            const safeMessage = escapeHtml(chat.message);

                            let html = '';

                            if (chat.is_me) {
                                html = `
                                    <div class="flex justify-end">
                                        <div class="bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none shadow-sm max-w-[80%]">
                                            <p class="text-sm whitespace-pre-line">${safeMessage}</p>
                                            <span class="text-[10px] text-blue-200 mt-2 block text-right">${chat.time}</span>
                                        </div>
                                    </div>`;
                            } else {
                                html = `
                                    <div class="flex justify-start">
                                        <div class="bg-white border border-slate-200 text-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                                            <p class="text-sm whitespace-pre-line">${safeMessage}</p>
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

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const msg = messageInput.value;

            if (!msg.trim()) return;

            messageInput.value = '';

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
        });

        let lastOfferSignature = null;
        let lastOrderStatus = null;

        function checkPriceOfferState() {
            fetch(`/chat/${orderId}/state`)
                .then(response => response.json())
                .then(data => {
                    if (!data) return;

                    if (lastOfferSignature === null) {
                        lastOfferSignature = data.offer_signature;
                        lastOrderStatus = data.order_status;
                        return;
                    }

                    const offerChanged = data.offer_signature !== lastOfferSignature;
                    const statusChanged = data.order_status !== lastOrderStatus;

                    if (offerChanged || statusChanged) {
                        window.location.reload();
                    }
                })
                .catch(() => {
                    // Abaikan error kecil supaya chat tidak terganggu
                });
        }

        checkPriceOfferState();
        setInterval(checkPriceOfferState, 3000);
    </script>
@endsection
