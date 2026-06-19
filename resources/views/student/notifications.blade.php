<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pesanan - UnilamKantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased max-w-md mx-auto min-h-screen shadow-lg relative pb-12">

    <div class="bg-white p-4 sticky top-0 z-40 border-b border-gray-100 flex items-center gap-3 shadow-sm">
        <a href="{{ route('student.home') }}" class="text-gray-500 hover:text-orange-500 transition text-sm p-1">
            <i class="fa-solid fa-arrow-left text-base"></i>
        </a>
        <h2 class="font-extrabold text-gray-900 text-base">Status Pesanan Kamu</h2>
    </div>

    <div class="p-4 space-y-3.5">
        @if(isset($groupedOrders) && $groupedOrders->isNotEmpty())
            @foreach($groupedOrders as $time => $ordersInGroup)
                @foreach($ordersInGroup as $order)
                    
                    <a href="{{ route('student.order.track', $order->id) }}" class="p-3.5 rounded-2xl border bg-white shadow-sm flex items-center justify-between border-orange-100 bg-gradient-to-r from-orange-50/20 to-transparent block hover:border-orange-200 transition transform active:scale-98">
    <div class="flex items-center gap-3 min-w-0 flex-1">
        
        @if($order->status == 'siap diambil')
            <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-sm flex-shrink-0">
                <i class="fa-solid fa-bell animate-bounce"></i>
            </div>
        @elseif($order->status == 'selesai')
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center text-green-600 text-sm flex-shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        @elseif($order->status == 'batal')
            <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center text-red-600 text-sm flex-shrink-0">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        @else 
            <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-sm flex-shrink-0">
                <i class="fa-solid fa-utensils animate-pulse"></i>
            </div>
        @endif
        
        <div class="min-w-0">
            <h4 class="font-bold text-xs text-gray-900 truncate">Pesanan di {{ $order->stand->stand_name ?? 'Kantin' }}</h4>
            
            @if($order->status == 'siap diambil')
                <p class="text-[11px] text-emerald-600 font-extrabold mt-0.5">🎉 Makanan siap diambil! Silakan ke stand.</p>
            @elseif($order->status == 'dimasak')
                <p class="text-[11px] text-amber-600 font-bold mt-0.5">🍳 Sedang dimasak oleh penjual...</p>
            @elseif($order->status == 'selesai')
                <p class="text-[11px] text-green-600 font-semibold mt-0.5">✅ Pesanan selesai. Selamat menikmati! Nyam~</p>
            @elseif($order->status == 'batal')
                <p class="text-[11px] text-red-500 font-medium mt-0.5">❌ Pesanan dibatalkan oleh stan.</p>
            @else
                <p class="text-[11px] text-gray-500 font-medium mt-0.5">⏳ Menunggu konfirmasi stand...</p>
            @endif
            
            <span class="text-[9px] text-gray-400 font-medium block mt-1">
                No. Antrean: {{ $order->id }} • {{ $order->updated_at->diffForHumans() }}
            </span>
        </div>
    </div>
    
    <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 pr-1"></i>
</a>

                @endforeach
            @endforeach
        @else
            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 text-center py-8 shadow-inner">
                <div class="text-gray-300 text-3xl mb-1">
                    <i class="fa-solid fa-bell-slash"></i>
                </div>
                <p class="text-[11px] text-gray-400 font-semibold">✨ Tidak ada pesanan aktif. Perutmu aman hari ini!</p>
            </div>
        @endif
    </div>

</body>
</html>