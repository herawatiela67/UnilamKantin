<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin - Keranjang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 antialiased max-w-md mx-auto min-h-screen shadow-lg relative pb-44">

    <header class="bg-white px-4 py-3 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <a href="{{ route('student.home') }}" class="text-gray-700 p-1 hover:text-orange-500 transition"><i class="fa-solid fa-arrow-left text-xl"></i></a>
        <h1 class="font-bold text-base text-gray-900">Keranjang Belanja</h1>
    </header>

    <main class="p-4">
        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-3 rounded-xl mb-4 text-xs font-bold border border-green-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-3 rounded-xl mb-4 text-xs font-bold border border-red-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="text-center py-20">
                <div class="text-gray-200 text-7xl mb-4"><i class="fa-solid fa-basket-shopping"></i></div>
                <p class="text-gray-400 text-sm mb-4 font-medium">Wah, keranjang belanjamu masih kosong nih.</p>
                <a href="{{ route('student.home') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs px-6 py-3 rounded-xl shadow-md tracking-wide transition transform active:scale-95">
                    Cari Makanan Dulu
                </a>
            </div>
        @else
            @php
                // 🟢 PERBAIKAN 2: Kelompokkan menu berdasarkan nama Stan Kuliner lewat relasi database
                $groupedCart = [];
                foreach($cartItems as $item) {
                    $standName = optional(optional($item->menu)->stand)->stand_name ?? 'Stan Kuliner'; 
                    $groupedCart[$standName][] = $item;
                }
            @endphp

            <div class="space-y-5 mb-6">
                @foreach($groupedCart as $standName => $items)
                    <div class="space-y-2">
                        <div class="bg-orange-50 px-3 py-2 rounded-xl border border-orange-100 flex items-center gap-2 text-xs text-orange-700 font-bold shadow-sm">
                            <i class="fa-solid fa-store"></i> {{ $standName }} 
                            <span class="ml-auto text-[10px] bg-orange-200 text-orange-800 px-1.5 py-0.5 rounded-md">{{ count($items) }} Menu</span>
                        </div>

                        <div class="space-y-2.5">
                            @foreach($items as $item)
                                @php
                                    // Mengambil gambar menu dengan aman melalui objek database
                                    $menuImage = optional($item->menu)->image;
                                    $menuImg = $menuImage ? (str_contains($menuImage, 'http') ? $menuImage : asset($menuImage)) : 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=100';
                                @endphp
                                <div class="bg-white p-3 rounded-xl border border-gray-100 flex gap-3 items-center shadow-sm">
                                    <img src="{{ $menuImg }}" class="w-16 h-16 rounded-lg object-cover bg-gray-50 flex-shrink-0">
                                    
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-sm text-gray-900 truncate">{{ optional($item->menu)->name ?? 'Menu Kuliner' }}</h4>
                                        <p class="text-xs text-gray-400 mb-1">Rp {{ number_format(optional($item->menu)->price, 0, ',', '.') }}</p>
                                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-md">Jumlah: {{ $item->quantity }}x</span>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm font-black text-gray-900 mb-2">Rp {{ number_format(optional($item->menu)->price * $item->quantity, 0, ',', '.') }}</p>
                                        
                                        <form action="{{ route('student.cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus {{ optional($item->menu)->name }} dari keranjang?')">
                                            @csrf
                                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm p-1 transition cursor-pointer">
                                                <i class="fa-regular fa-trash-can text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('student.checkout') }}" method="POST" id="form-checkout">
                @csrf

                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-3 mb-6">
                    <h3 class="font-bold text-sm text-gray-900 border-b pb-2 mb-1">
                        <i class="fa-solid fa-wallet text-orange-500 mr-1"></i> Metode Pembayaran
                    </h3>
                    
                    <label class="flex items-center justify-between p-3 rounded-xl border border-gray-200 cursor-pointer hover:border-orange-200 transition bg-gray-50 select-none">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center text-base">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Bayar Tunai / Cash di Tempat</p>
                                <p class="text-[10px] text-gray-400">Bayar langsung di meja stan pas ambil makanan</p>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="cash" checked onclick="togglePaymentChannels(false)" class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500 accent-orange-500">
                    </label>

                    <label class="flex items-center justify-between p-3 rounded-xl border border-gray-200 cursor-pointer hover:border-orange-200 transition bg-gray-50 select-none">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-base">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900">E-Wallet / Transfer Bank</p>
                                <p class="text-[10px] text-gray-400">DANA, OVO, BCA, Mandiri, dll.</p>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="ewallet" onclick="togglePaymentChannels(true)" class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500 accent-orange-500">
                    </label>

                    <div id="digital-channels" class="hidden pt-2 space-y-2 border-t border-dashed border-gray-200 transition duration-300">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pilih Channel Pembayaran:</p>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer bg-white text-xs font-semibold hover:border-blue-400">
                                <input type="radio" name="payment_channel" value="dana" checked class="accent-blue-500">
                                <span class="text-blue-600 font-extrabold tracking-wide text-xs">DANA</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer bg-white text-xs font-semibold hover:border-purple-400">
                                <input type="radio" name="payment_channel" value="ovo" class="accent-purple-600">
                                <span class="text-purple-700 font-black text-xs">OVO</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer bg-white text-xs font-semibold hover:border-blue-600">
                                <input type="radio" name="payment_channel" value="bca" class="accent-blue-800">
                                <span class="text-blue-900 font-black text-xs">M-Banking BCA</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer bg-white text-xs font-semibold hover:border-amber-500">
                                <input type="radio" name="payment_channel" value="mandiri" class="accent-amber-500">
                                <span class="text-amber-500 font-black text-xs">Livin' Mandiri</span>
                            </label>
                        </div>
                    </div>
                </div>

                @if(count($groupedCart) > 1)
                    <div class="mb-4 bg-blue-50 p-3 rounded-xl border border-blue-100 flex items-start gap-2 text-[11px] text-blue-700 font-medium leading-relaxed shadow-sm">
                        <i class="fa-solid fa-circle-info mt-0.5 text-xs text-blue-500"></i>
                        <span>Info Kampus: Karena item berasal dari <strong>{{ count($groupedCart) }} stan berbeda</strong>, pesananmu otomatis akan dicetak menjadi {{ count($groupedCart) }} nota terpisah setelah diklik.</span>
                    </div>
                @endif

                <div class="bg-white p-4 rounded-xl border border-gray-100 space-y-2.5 shadow-sm mb-6">
                    <h3 class="font-bold text-sm text-gray-900 border-b pb-2 mb-1">Ringkasan Pembayaran</h3>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Subtotal Makanan</span>
                        <span class="font-semibold text-gray-700">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Biaya Aplikasi</span>
                        <span class="text-green-600 font-bold">Gratis</span>
                    </div>
                    <hr class="border-gray-100 my-1">
                    <div class="flex justify-between text-sm font-bold text-gray-900">
                        <span>Total Tagihan</span>
                        <span class="text-orange-500 font-black text-base">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="fixed bottom-16 max-w-md w-full bg-white border-t border-gray-100 p-4 left-0 right-0 mx-auto z-50 shadow-2xl">
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold text-center py-3 rounded-xl shadow-lg transition tracking-wide text-sm flex items-center justify-center gap-2 transform active:scale-98 cursor-pointer">
                        <i class="fa-solid fa-receipt"></i> Pesan Sekarang
                    </button>
                </div>
            </form>
        @endif
    </main>

<x-navbar-student active="cart" />
    <script>
        function togglePaymentChannels(show) {
            const container = document.getElementById('digital-channels');
            if (show) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
    </script>
</body>
</html>