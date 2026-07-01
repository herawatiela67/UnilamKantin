<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $stand->stand_name }} - UnilamKantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 antialiased max-w-md mx-auto min-h-screen shadow-lg relative pb-28">

    <div class="relative h-52 w-full bg-gray-200">
        @php
            $standImg = $stand->image ? asset('storage/' . $stand->image) : 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500';
        @endphp
        <img src="{{ $standImg }}" alt="{{ $stand->stand_name }}" class="w-full h-full object-cover">
        
        <div class="absolute inset-0 bg-black/40"></div>

        <a href="{{ route('student.home') }}" class="absolute top-4 left-4 w-9 h-9 bg-white/90 rounded-full flex items-center justify-center text-gray-800 shadow hover:bg-white transition">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>

        <div class="absolute bottom-4 left-4 right-4 text-white">
            <div class="flex items-center gap-2 mb-1.5">
                <div class="bg-orange-500 text-white text-[10px] font-bold px-2 py-0.5 rounded flex items-center gap-1">
                    <i class="fa-solid fa-star text-amber-300 text-[9px]"></i> 
                    @php
                        $ratingOtomatis = 4.4 + min(($stand->total_terjual / 5) * 0.1, 0.6);
                    @endphp
                    {{ number_format($ratingOtomatis, 1) }}
                </div>
            </div>
            <h1 class="text-xl font-bold tracking-wide">{{ $stand->stand_name }}</h1>
            <p class="text-xs text-gray-200 mt-0.5">{{ $stand->description ?? 'Lapak Nomor ' . ($stand->stand_number ?? '-') }}</p>
        </div>
    </div>

    <main class="p-4 space-y-4">
        @forelse($menus as $menu)
    @php
        $status = strtolower($menu->status ?? 'available');
        $isSoldOut = ($status === 'habis' || $status === 'empty');
        $menuImg = $menu->image ? (str_contains($menu->image, 'http') ? $menu->image : asset($menu->image)) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500';
    @endphp

    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex gap-4 items-start relative">
        
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <h3 class="font-bold text-base text-gray-900 truncate {{ $isSoldOut ? 'line-through text-gray-400' : '' }}">
                    {{ $menu->name ?? 'Menu Kuliner' }}
                </h3>
                @if(str_contains(strtolower($menu->name), 'geprek') && !$isSoldOut)
                    <span class="bg-red-50 text-red-600 text-[9px] font-extrabold px-1.5 py-0.5 rounded uppercase">Spicy</span>
                @endif
            </div>
            <p class="text-xs text-gray-400 mt-1 line-clamp-2 leading-relaxed">
                {{ $menu->description ?? 'Tidak ada deskripsi makanan.' }}
            </p>
            <p class="text-sm font-extrabold text-gray-900 mt-2 mb-3">
                Rp{{ number_format($menu->price, 0, ',', '.') }}
            </p>

            @if(!$isSoldOut)
                <form action="{{ route('student.cart.add', $menu->id) }}" method="POST" class="flex items-center gap-2 flex-wrap">
                    @csrf
                    
                    <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50 p-0.5 shadow-inner">
                        <button type="button" onclick="decrementQty({{ $menu->id }})" class="w-7 h-7 flex items-center justify-center text-gray-500 font-bold hover:bg-gray-200 rounded-lg transition cursor-pointer">
                            <i class="fa-solid fa-minus text-[9px]"></i>
                        </button>

                        <input type="number" id="quantity_{{ $menu->id }}" name="quantity" value="1" min="1" class="w-7 text-center text-xs font-black bg-transparent border-none focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                        <button type="button" onclick="incrementQty({{ $menu->id }})" class="w-7 h-7 flex items-center justify-center text-orange-600 font-bold hover:bg-orange-100 rounded-lg transition cursor-pointer">
                            <i class="fa-solid fa-plus text-[9px]"></i>
                        </button>
                    </div>

                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-xl text-xs font-bold transition shadow-sm cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-basket-shopping text-[10px]"></i> Tambah
                    </button>
                </form>
            @else
                <div class="flex items-center mt-2">
                    <span class="bg-gray-100 text-gray-400 font-bold text-xs px-4 py-2 rounded-xl cursor-not-allowed border border-gray-200 select-none">
                        ❌ Stok Habis
                    </span>
                </div>
            @endif
        </div>

        <div class="relative w-24 h-24 bg-gray-100 rounded-2xl overflow-hidden flex-shrink-0 shadow-md">
            <img src="{{ $menuImg }}" alt="{{ $menu->name }}" class="w-full h-full object-cover {{ $isSoldOut ? 'grayscale contrast-75 brightness-75' : '' }}">
            
            @if($isSoldOut)
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center p-1">
                    <span class="bg-red-600 text-white font-black text-[9px] uppercase tracking-wider px-2 py-1 rounded-md shadow-md border border-red-500 animate-pulse text-center w-11/12">
                        SOLD OUT
                    </span>
                </div>
            @endif
        </div>

    </div>
@empty
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mx-auto mb-3">
            <i class="fa-solid fa-utensils text-2xl"></i>
        </div>
        <h3 class="font-bold text-gray-700 text-sm">Menu belum tersedia</h3>
        <p class="text-xs text-gray-400 max-w-xs mx-auto mt-1 px-4">Etalase makanan di stan ini masih kosong di database.</p>
    </div>
@endforelse
    </main>

    <script>
    function incrementQty(menuId) {
        const input = document.getElementById('quantity_' + menuId);
        if (input) {
            input.value = parseInt(input.value) + 1;
        }
    }

    function decrementQty(menuId) {
        const input = document.getElementById('quantity_' + menuId);
        if (input && parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
    </script>

    <x-navbar-student active="home" />

</body>
</html>