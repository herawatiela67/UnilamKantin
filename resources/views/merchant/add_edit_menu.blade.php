<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu ? 'Edit Menu Jualan' : 'Tambah Menu Baru' }} - UnilamKantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#F5F5F5] text-gray-800 antialiased max-w-md mx-auto min-h-screen flex flex-col shadow-lg bg-white">

    <div class="bg-white p-4 sticky top-0 z-10 shadow-sm flex items-center gap-4">
        <a href="{{ route('merchant.home') }}" class="text-gray-600 hover:text-gray-900 text-xl">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-lg font-bold text-[#962D15]">{{ $menu ? 'Edit Menu Jualan' : 'Tambah Menu Baru' }}</h1>
    </div>

    <div class="p-6">
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-xs font-bold mb-6 border border-red-100">
                <ul class="list-disc ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $menu ? route('merchant.menu.update', $menu->id) : route('merchant.menu.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @if($menu)
                @method('PUT') @endif

            <div class="mb-5">
                <label class="block font-bold text-sm text-gray-700 mb-2 uppercase tracking-wide">Foto Menu Makanan/Minuman</label>
                <div class="relative w-full h-40 bg-[#F0EDED] rounded-2xl border border-gray-300 overflow-hidden flex flex-col items-center justify-center group">
                    @if($menu && $menu->image)
                        <img src="{{ str_contains($menu->image, 'http') ? $menu->image : asset($menu->image) }}" class="absolute inset-0 w-full h-full object-cover" id="preview-img">
                    @else
                        <img class="absolute inset-0 w-full h-full object-cover hidden" id="preview-img">
                    @endif
                    <div class="relative z-10 text-center p-4 bg-white/70 rounded-xl backdrop-blur-sm cursor-pointer hover:bg-white transition">
                        <i class="fa-solid fa-camera text-gray-600 text-lg mb-1"></i>
                        <p class="text-[11px] font-bold text-gray-700">Pilih / Ganti Foto</p>
                        <input type="file" name="image" id="image-file" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(event)">
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block font-bold text-sm text-gray-700 mb-2 uppercase tracking-wide">Nama Menu</label>
                <div class="bg-[#F0EDED] rounded-2xl px-4 py-3.5 flex items-center gap-3">
                    <i class="fa-solid fa-utensils text-gray-400"></i>
                    <input type="text" name="name" value="{{ old('name', $menu ? $menu->name : '') }}" placeholder="Misal: Nasi Goreng Gila" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400 font-medium" required>
                </div>
            </div>

            <div class="mb-5">
                <label class="block font-bold text-sm text-gray-700 mb-2 uppercase tracking-wide">Harga (Rp)</label>
                <div class="bg-[#F0EDED] rounded-2xl px-4 py-3.5 flex items-center gap-3">
                    <span class="font-bold text-gray-400 text-sm">Rp</span>
                    <input type="number" name="price" value="{{ old('price', $menu ? $menu->price : '') }}" placeholder="Misal: 15000" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400 font-medium" required>
                </div>
            </div>

            <div class="mb-5">
                <label class="block font-bold text-sm text-gray-700 mb-2 uppercase tracking-wide">Status Menu</label>
                <div class="bg-[#F0EDED] rounded-2xl px-4 py-3.5 flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-gray-400"></i>
                    <select name="status" class="bg-transparent w-full text-sm focus:outline-none font-medium text-gray-700 cursor-pointer">
                        <option value="available" {{ old('status', $menu ? $menu->status : 'available') == 'available' ? 'selected' : '' }}>Tersedia / Masih Ada</option>
                        <option value="unavailable" {{ old('status', $menu ? $menu->status : '') == 'unavailable' ? 'selected' : '' }}>Habis / Sold Out</option>
                    </select>
                </div>
            </div>

            <div class="mb-8">
                <label class="block font-bold text-sm text-gray-700 mb-2 uppercase tracking-wide">Deskripsi / Keterangan</label>
                <div class="bg-[#F0EDED] rounded-2xl px-4 py-3.5">
                    <textarea name="description" rows="3" placeholder="Misal: Pedas level 1-5, pakai telur ceplok..." class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400 font-medium resize-none">{{ old('description', $menu ? $menu->description : '') }}</textarea>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#FF5722] hover:bg-orange-600 text-white font-extrabold py-4 rounded-full shadow-lg transition active:scale-95 text-center block text-sm tracking-wide">
                {{ $menu ? 'PERBARUI MENU' : 'SIMPAN MENU JUALAN' }}
            </button>
        </form>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview-img');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>