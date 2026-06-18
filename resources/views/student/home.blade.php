<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased max-w-md mx-auto min-h-screen shadow-lg relative pb-24">

    <header class="bg-white px-4 py-3 flex justify-between items-center border-b border-gray-100 sticky top-0 z-50">
        <div>
            <div class="flex items-center gap-1">
                <span class="font-bold text-sm text-black">Kantin Pusat</span>
                <i class="fa-solid fa-chevron-down text-xs text-gray-600"></i>
            </div>
            <span class="text-xs text-gray-400">UnilamKantin</span>
        </div>
        <div class="flex items-center gap-2">
            <button class="text-gray-700 p-2"><i class="fa-regular fa-bell text-xl"></i></button>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-red-500 p-2" onclick="return confirm('Keluar dari KantinQuick?')">
                    <i class="fa-solid fa-right-from-bracket text-xl"></i>
                </button>
            </form>
        </div>
    </header>
    <main class="p-4">
        <div class="bg-gray-100 rounded-xl px-4 py-2.5 flex items-center gap-3 mb-5">
            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
            <input type="text" placeholder="Cari makanan atau minuman..." class="bg-transparent w-full text-sm focus:outline-none">
        </div>

        <div class="flex gap-2 mb-6">
            <button class="bg-orange-500 text-white font-bold text-sm px-4 py-1.5 rounded-full">Semua</button>
            <button class="bg-gray-200 text-gray-700 text-sm px-4 py-1.5 rounded-full hover:bg-orange-500 hover:text-white transition">Makanan</button>
            <button class="bg-gray-200 text-gray-700 text-sm px-4 py-1.5 rounded-full hover:bg-orange-500 hover:text-white transition">Minuman</button>
        </div>

        <div class="flex justify-between items-center mb-3">
            <h2 class="font-bold text-base text-gray-900">Merchant Populer</h2>
        </div>
        <div class="space-y-4">
        @foreach($stands as $stand)
            @php
                // Ambil foto stan dari database atau gunakan dummy jika kosong
                $imgUrl = $stand->image ? asset('storage/' . $stand->image) : 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500';
            @endphp

            <a href="{{ route('student.stand.detail', $stand->id) }}" class="block bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md hover:border-orange-100 transition duration-200">
                
                <div class="relative h-36 bg-gray-100">
                    <img src="{{ $imgUrl }}" alt="{{ $stand->stand_name }}" class="w-full h-full object-cover">
                    
                    @if(strtolower($stand->status ?? 'open') === 'open')
                        <div class="absolute top-2.5 left-2.5 bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded shadow-sm flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span> Buka
                        </div>
                    @else
                        <div class="absolute top-2.5 left-2.5 bg-red-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded shadow-sm">
                            Tutup
                        </div>
                    @endif
                </div>

                <div class="p-3">
                    <h3 class="font-bold text-sm text-gray-900 truncate">{{ $stand->stand_name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">
                        {{ $stand->description ?? 'Lapak Nomor ' . ($stand->stand_number ?? '-') }}
                    </p>
                    
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-50">
                       @if($stand->orders_count > 0)
                            <span class="text-[10px] bg-amber-50 text-amber-600 px-2 py-0.5 rounded font-bold border border-amber-100">
                                <i class="fa-solid fa-fire text-[9px] mr-1 animate-bounce"></i> {{ $stand->orders_count }} Antrean
                            </span>
                        @else
                            <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-bold border border-emerald-100">
                                <i class="fa-solid fa-bolt text-[9px] mr-1"></i> Bebas Antrean
                            </span>
                        @endif
                        <span class="text-xs font-bold text-orange-500 flex items-center gap-1">
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i> 4.8
                        </span>
                    </div>
                </div>

            </a>
        @endforeach
        </div>
    </main>
    @if(isset($activeOrdersTracking) && $activeOrdersTracking->isNotEmpty())
        @php $orderTerbaru = $activeOrdersTracking->first(); @endphp
        
        <a href="{{ route('student.order.track', $orderTerbaru->id) }}" class="fixed bottom-20 left-4 right-4 bg-[#1E1E1F] text-white p-3.5 rounded-2xl shadow-xl flex items-center gap-3 cursor-pointer hover:bg-zinc-800 transition transform active:scale-98 z-40">
            <div class="bg-orange-500 p-2.5 rounded-xl animate-pulse">
                <i class="fa-solid fa-utensils text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-sm">Pesanan {{ ucfirst($orderTerbaru->status) }}</h4>
                <p class="text-xs text-gray-400 truncate">{{ $orderTerbaru->stand->stand_name ?? 'Stan Kantin' }} • Sentuh untuk lacak</p>
            </div>
            @if($activeOrdersTracking->count() > 1)
                <span class="bg-zinc-800 text-amber-400 font-bold text-[10px] px-2 py-1 rounded-xl border border-zinc-700">
                    +{{ $activeOrdersTracking->count() - 1 }} Stan
                </span>
            @endif
            <i class="fa-solid fa-chevron-right text-xs text-gray-500"></i>
        </a>
    @endif
    
    <x-navbar-student active="home" />

</body>
</html>