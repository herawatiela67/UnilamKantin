 @if($menuTerbaru != null)
        <div onclick="scrollToMenu('menu-{{ $menuTerbaru->menu_id ?? $menuTerbaru->id }}')" class="cursor-pointer bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl p-4 text-white shadow-md relative overflow-hidden transition transform active:scale-95">
            <div class="absolute -right-6 -bottom-6 opacity-10 text-8xl">
                <i class="fa-solid fa-utensils"></i>
            </div>
            
            <span class="bg-emerald-500/30 text-emerald-100 text-[10px] font-bold uppercase px-2.5 py-0.5 rounded-full tracking-wider border border-emerald-500/20">
                ✨ Menu Baru Rilis
            </span>
            
            <h3 class="font-extrabold text-base mt-2 leading-tight">
                Cobain {{ $menuTerbaru->menu_name }} <br>
                di <span class="text-amber-200">{{ $menuTerbaru->stand_name }}</span>!
            </h3>
            
            <p class="text-[10px] text-orange-100 mt-1.5 font-medium flex items-center gap-1">
                <i class="fa-solid fa-tags text-[9px]"></i> 
                Hanya Rp {{ number_format($menuTerbaru->price, 0, ',', '.') }} • Yuk serbu sebelum kehabisan!
            </p>
        </div>
    @else
        <div class="space-y-4">
    <button onclick="openSpinnerModal()" class="w-full bg-gradient-to-r from-orange-500 to-amber-500 text-white p-4 rounded-2xl shadow-md flex items-center justify-between hover:opacity-90 transition active:scale-98">
        <div class="flex items-center gap-3">
            <div class="bg-white/20 p-2 rounded-xl text-xl animate-spin" style="animation-duration: 3s;">
                <i class="fa-solid fa-circle-notch"></i>
            </div>
            <div class="text-left">
                <h4 class="font-extrabold text-sm text-white">Bingung Pilih Menu?</h4>
                <p class="text-[11px] text-orange-100 font-medium">Biar Roda Keberuntungan yang pilihin!</p>
            </div>
        </div>
        <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
    </button>
</div>

<div id="spinnerModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 relative shadow-2xl transform scale-95 transition-all">
        
        <button onclick="closeSpinnerModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 bg-gray-100 p-2 rounded-full w-8 h-8 flex items-center justify-center text-xs">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="text-center mt-2">
            <h3 class="font-extrabold text-gray-800 text-base">Roda Keberuntungan Kuliner</h3>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Putar untuk tentukan menu makanmu hari ini!</p>
        </div>

        <div class="flex flex-col items-center justify-center my-6 relative">
            <div class="absolute -top-2 z-10 text-orange-600 text-2xl filter drop-shadow">
                <i class="fa-solid fa-caret-down"></i>
            </div>
            
            <div class="bg-gray-50 p-3 rounded-full border border-gray-100 shadow-inner">
                <canvas id="wheelCanvas" width="260" height="260" class="transition-transform ease-out duration-[5000ms]"></canvas>
            </div>
        </div>

        <button id="spinButton" onclick="spinTheWheel()" class="w-full bg-orange-500 text-white font-bold p-3.5 rounded-xl shadow-lg shadow-orange-500/20 hover:bg-orange-600 transition active:scale-95 text-sm">
            🔥 PUTAR RODA NOW!
        </button>

        <div id="gachaResult" class="hidden mt-4 p-4 bg-orange-50 border border-orange-100 rounded-2xl text-center animate-fade-in">
            <span class="text-[10px] uppercase tracking-wider text-orange-500 font-extrabold block">Menu Terpilih Untukmu:</span>
            <h4 id="resultMenuName" class="font-black text-gray-800 text-base mt-0.5">Nama Makanan</h4>
            <p id="resultMenuPrice" class="text-xs text-amber-600 font-bold mt-0.5">Rp 0</p>
            <div class="mt-3 flex gap-2">
                <button id="goToMenuBtn" class="flex-1 bg-white border border-orange-200 text-orange-600 font-bold text-xs p-2 rounded-xl shadow-sm hover:bg-orange-50 transition">
                    Lihat Stand
                </button>
            </div>
        </div>

    </div>
</div>
    @endif
