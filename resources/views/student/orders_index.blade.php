<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin - Riwayat Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased max-w-md mx-auto min-h-screen flex flex-col pb-24 shadow-lg bg-white relative">

    <header class="bg-white px-4 py-3 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <a href="{{ route('student.home') }}" class="text-gray-700 p-1 hover:text-orange-500 transition">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h1 class="font-bold text-base text-gray-900">Riwayat Pesanan</h1>
    </header>

    <main class="p-4 flex-1">
        @if($orders->isEmpty())
            <div class="text-center py-20 text-gray-300">
                <i class="fa-solid fa-receipt text-6xl mb-3 text-gray-200"></i>
                <p class="text-xs font-medium text-gray-400">Kamu belum pernah memesan makanan nih.</p>
                <a href="{{ route('student.home') }}" class="mt-4 inline-block bg-orange-500 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md">
                    Pesan Sekarang
                </a>
            </div>
        @else
            <div class="space-y-3.5">
                @foreach($orders as $order)
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            
                            @if($order->status == 'pending')
                                <span class="bg-amber-50 text-amber-600 font-extrabold px-2 py-0.5 rounded-md text-[10px] uppercase">Menunggu</span>
                            @elseif($order->status == 'dimasak')
                                <span class="bg-orange-50 text-orange-600 font-extrabold px-2 py-0.5 rounded-md text-[10px] uppercase">Dimasak</span>
                            @elseif($order->status == 'ready')
                                <span class="bg-blue-50 text-blue-600 font-extrabold px-2 py-0.5 rounded-md text-[10px] uppercase">Siap Diambil</span>
                            @else
                                <span class="bg-emerald-50 text-emerald-600 font-extrabold px-2 py-0.5 rounded-md text-[10px] uppercase">Selesai</span>
                            @endif
                        </div>

                        <div class="border-t border-b border-gray-50 py-2 flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 text-lg">
                                <i class="fa-solid fa-bowl-food"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-xs text-gray-400 uppercase tracking-wider">
                                    {{ optional($order->stand)->stand_name ?? 'Stan Kuliner' }}
                                </h3>
                                <p class="text-sm font-bold text-gray-800 truncate">
                                    ID Pesanan: #{{ $order->id }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400">Total Tagihan</p>
                                <p class="text-sm font-black text-orange-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <x-navbar-student active="orders" />

</body>
</html>