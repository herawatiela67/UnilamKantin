<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu Stand - UnilamKantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F5F5F5] text-gray-800 antialiased max-w-md mx-auto min-h-screen flex flex-col pb-24 shadow-lg bg-white relative">

    <div class="bg-[#962D15] p-5 text-white rounded-b-3xl shadow-md sticky top-0 z-50">
        <div class="flex justify-between items-center mb-4">
            <div>
                <span class="text-xs opacity-75 font-medium">Kelola Produk Kuliner</span>
                <h1 class="text-xl font-bold tracking-wide">Menu Makanan</h1>
            </div>
            <a href="{{ route('merchant.menu.create') }}" class="bg-[#FF5722] hover:bg-orange-600 text-white font-bold px-3 py-1.5 rounded-xl text-xs flex items-center gap-1 shadow-md transition">
                <i class="fa-solid fa-plus"></i> Tambah Menu
            </a>
        </div>
    </div>

    <div class="p-4 flex-1 space-y-3">
        @foreach($menus as $menu)
        <div class="bg-white rounded-2xl p-4 shadow-sm flex gap-4 items-center mb-4 border border-gray-100 relative group">
            <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 relative">
                @if($menu->image)
                    <img src="{{ str_contains($menu->image, 'http') ? $menu->image : asset($menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-utensils text-lg"></i></div>
                @endif
            </div>

            <div class="flex-1 min-w-0 pr-16"> 
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-bold text-sm text-gray-900 truncate tracking-tight">{{ $menu->name }}</h3>
                    <form action="{{ route('merchant.menu.toggle', $menu->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="cursor-pointer">
                            @if($menu->status === 'available')
                                <span class="bg-emerald-50 text-emerald-600 text-[9px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider">Tersedia</span>
                            @else
                                <span class="bg-red-50 text-red-600 text-[9px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider">Habis</span>
                            @endif
                        </button>
                    </form>
                </div>
                <p class="text-xs font-black text-[#962D15] mb-1">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                <p class="text-[11px] text-gray-500 line-clamp-1 leading-relaxed">{{ $menu->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>

            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex flex-col gap-2">
                <a href="{{ route('merchant.menu.edit', $menu->id) }}" class="w-8 h-8 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xs transition shadow-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                <form action="{{ route('merchant.menu.delete', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu permanen?')">
                    @csrf @method('DELETE') 
                    <button type="submit" class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-500 rounded-xl flex items-center justify-center text-xs transition shadow-sm"><i class="fa-solid fa-trash-can"></i></button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="fixed bottom-0 max-w-md w-full bg-white border-t border-gray-100 shadow-lg z-50 left-0 right-0 mx-auto">
        <nav class="grid grid-cols-2 w-full h-16 bg-white">
            <a href="{{ route('merchant.home') }}" class="flex flex-col items-center justify-center text-gray-400 hover:text-orange-500 transition">
                <i class="fa-solid fa-receipt text-xl"></i>
                <span class="text-[10px] font-medium mt-1">Pesanan</span>
            </a>
            <a href="{{ route('merchant.menu.index') }}" class="flex flex-col items-center justify-center text-[#962D15] transition">
                <i class="fa-solid fa-utensils text-xl"></i>
                <span class="text-[10px] font-black mt-1">Menu Stand</span>
            </a>
        </nav>
    </div>

</body>
</html>