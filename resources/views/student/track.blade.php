<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin- Lacak Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 antialiased max-w-md mx-auto min-h-screen shadow-lg relative pb-28">

    <header class="bg-white px-4 py-3 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <a href="{{ route('student.home') }}" class="text-gray-700 p-1 hover:text-orange-500 transition">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="font-bold text-base text-gray-900">Status Pesanan</h1>
            <p class="text-[10px] text-gray-400 font-medium">ID Transaksi: {{ $order->id }}</p>
        </div>
    </header>

    <main class="p-4 space-y-4">
        
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
    <div>
        <span class="text-[10px] bg-orange-50 text-orange-600 font-bold px-2 py-0.5 rounded uppercase tracking-wider">Kantin Pusat</span>
        
        @if($order->stand)
            <h2 class="font-black text-base text-gray-900 mt-1">
                {{ $order->stand->stand_name }}
            </h2>
            <p class="text-xs text-gray-400">
                Lapak Nomor: <span class="font-bold text-gray-700">{{ $order->stand->stand_number ?? 'Nomor Lapak Kosong di DB' }}</span>
            </p>
        @else
            <h2 class="font-black text-base text-red-500 mt-1">
                Gagal Memuat Relasi Stan
            </h2>
            <p class="text-[10px] text-gray-400 font-mono bg-gray-50 p-1 rounded mt-1">
                Isi stand_id di order ini: {{ $order->stand_id ?? 'NULL (Kosong)' }}
            </p>
        @endif
    </div>
    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xl">
        <i class="fa-solid fa-store"></i>
    </div>
</div>

    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-6 relative">
            @php $status = strtolower($order->status); @endphp

            <div class="absolute left-8 top-8 bottom-8 w-0.5 bg-gray-100 -z-0"></div>

            <div class="flex gap-4 relative z-10">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ in_array($status, ['diterima', 'dimasak', 'siap diambil', 'selesai']) ? 'bg-orange-500 text-white shadow-md shadow-orange-200' : 'bg-gray-100 text-gray-400' }}">
                    <i class="fa-solid fa-check text-[10px]"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold {{ in_array($status, ['diterima', 'dimasak', 'siap diambil', 'selesai']) ? 'text-gray-900' : 'text-gray-400' }}">Pesanan Diterima</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Penjual menyetujui dan mencetak pesanan.</p>
                </div>
            </div>

            <div class="flex gap-4 relative z-10">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ in_array($status, ['dimasak', 'siap diambil', 'selesai']) ? 'bg-orange-500 text-white shadow-md shadow-orange-200' : 'bg-gray-100 text-gray-400' }}">
                    <i class="fa-solid fa-fire-burner text-[10px]"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold {{ in_array($status, ['dimasak', 'siap diambil', 'selesai']) ? 'text-gray-900' : 'text-gray-400' }}">Sedang Dimasak</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Koki stand sedang menyiapkan makananmu.</p>
                </div>
            </div>

            <div class="flex gap-4 relative z-10">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ in_array($status, ['siap diambil', 'selesai']) ? 'bg-orange-500 text-white shadow-md shadow-orange-200' : 'bg-gray-100 text-gray-400' }}">
                    <i class="fa-solid fa-box-archive text-[10px]"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold {{ in_array($status, ['siap diambil', 'selesai']) ? 'text-gray-900' : 'text-gray-400' }}">Siap Diambil</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Makanan matang! Silakan ambil ke meja lapak.</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm space-y-3">
            <h3 class="font-bold text-xs text-gray-400 uppercase tracking-wider border-b pb-2">Item Yang Dibeli</h3>
            
            <div class="space-y-3">
                {{-- Kita loop lewat relasi orderDetails (atau orderItems sesuaikan dengan modelmu) --}}
                @foreach($order->orderDetails as $detail)
                    @php
                        $menu = $detail->menu;
                        $menuImg = $menu && $menu->image ? (str_contains($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image)) : 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=100';
                    @endphp
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $menuImg }}" class="w-10 h-10 rounded-lg object-cover bg-gray-50 border flex-shrink-0">
                            <div class="min-w-0">
                                <p class="font-bold text-gray-800 truncate">{{ $menu->name ?? 'Menu Terhapus' }}</p>
                                <p class="text-xs text-gray-400">Rp{{ number_format($detail->price, 0, ',', '.') }} x {{ $detail->quantity }}</p>
                            </div>
                        </div>
                        <span class="font-bold text-gray-900 flex-shrink-0">
                            Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <hr class="border-gray-100 my-2">

            <div class="space-y-2 text-xs">
                <div class="flex justify-between text-gray-500">
                    <span>Metode Pembayaran</span>
                    <span class="font-bold text-gray-700 uppercase">
                        @if($order->payment_method === 'cash')
                            <i class="fa-solid fa-money-bill-wave text-green-500 mr-0.5"></i> Cash di Tempat
                        @else
                            <i class="fa-solid fa-credit-card text-blue-500 mr-0.5"></i> E-Wallet ({{ strtoupper($order->payment_channel ?? 'Digital') }})
                        @endif
                    </span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Biaya Layanan</span>
                    <span class="text-green-600 font-bold">Gratis</span>
                </div>
                <div class="flex justify-between font-black text-sm text-gray-900 pt-2 border-t border-gray-50">
                    <span>Total Bayar</span>
                    <span class="text-orange-500 text-base font-black">Rp{{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </main>

    <x-navbar-student active="orders" />

</body>
</html>