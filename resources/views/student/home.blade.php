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

<header class="bg-white px-5 pt-5 pb-3 flex justify-between items-center sticky top-0 z-50 shadow-sm border-b border-gray-50">
    <div>
        <div class="flex items-center gap-1.5 text-orange-600">
            <i class="fa-solid fa-location-dot text-sm"></i>
            <span class="font-extrabold text-sm text-gray-900">UnilamKantin</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-500"></i>
        </div>
        @php
        $jam = date('H');
        if ($jam >= 5 && $jam < 11) {
            $sapaan = 'Mau sarapan apa pagi ini';
        } elseif ($jam >= 11 && $jam < 15) {
            $sapaan = 'Mau makan siang apa hari ini';
        } elseif ($jam >= 15 && $jam < 18) {
            $sapaan = 'Mau nyemil apa sore ini';
        } else {
            $sapaan = 'Mau makan malam apa malam ini';
        }
        @endphp 
    <span class="text-xs font-medium text-gray-400 block mt-0.5">
        {{ $sapaan }}, {{ Auth::user()->name }}? 👋
    </span>
    </div>

    <div class="flex items-center gap-1 relative">
    <button onclick="toggleNotificationDropdown()" class="text-gray-600 hover:text-orange-500 p-2 relative focus:outline-none transition active:scale-95">
        <i class="fa-regular fa-bell text-xl"></i>
        @if(isset($groupedOrders) && $groupedOrders->isNotEmpty())
            <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[9px] font-extrabold rounded-full flex items-center justify-center animate-pulse">
                {{ $groupedOrders->flatten()->count() }}
            </span>
        @endif
    </button>

    <div id="notificationDropdown" class="hidden absolute right-0 top-12 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
        <div class="p-3 border-b border-gray-50 flex justify-between items-center bg-gray-50">
            <span class="font-extrabold text-xs text-gray-700 flex items-center gap-1">
                <i class="fa-solid fa-bell text-orange-500"></i> Status Pesanan Kamu
            </span>
            <span class="text-[10px] text-gray-400 font-medium animate-pulse flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Real-time
            </span>
        </div>

        <div class="max-h-64 overflow-y-auto divide-y divide-gray-50">
            @if(isset($groupedOrders) && $groupedOrders->isNotEmpty())
                @foreach($groupedOrders as $time => $ordersInGroup)
                    @foreach($ordersInGroup as $order)
                        <a href="{{ route('student.order.track', $order->id) }}" class="p-3 hover:bg-orange-50/50 transition flex gap-2.5 items-start block">
                            <div class="mt-1">
                                @if($order->status == 'siap diambil')
                                    <span class="flex h-2 w-2 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                @elseif($order->status == 'dimasak')
                                    <span class="flex h-2 w-2 rounded-full bg-amber-500"></span>
                                @else
                                    <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-800 font-semibold leading-snug">
                                    @if($order->status == 'siap diambil')
                                        📢 <span class="text-emerald-600 font-bold">Pesanan Siap Diambil!</span> Yuk ambil di <span class="underline">{{ $order->stand->stand_name ?? 'Kantin' }}</span>.
                                    @elseif($order->status == 'dimasak')
                                        🍳 Pesananmu <span class="text-amber-600 font-bold">sedang dimasak</span> oleh {{ $order->stand->stand_name ?? 'Kantin' }}.
                                    @else
                                        📩 Pesanan <span class="text-blue-600 font-bold">telah diterima</span> oleh {{ $order->stand->stand_name ?? 'Kantin' }}.
                                    @endif
                                </p>
                                <span class="text-[9px] text-gray-400 font-medium block mt-1">
                                    No. Antrean: #{{ $order->id }} • Klik untuk detail
                                </span>
                            </div>
                        </a>
                    @endforeach
                @endforeach
            @else
                <div class="p-6 text-center">
                    <div class="text-gray-300 text-3xl mb-1">
                        <i class="fa-solid fa-bell-slash"></i>
                    </div>
                    <p class="text-xs text-gray-400 font-medium">Belum ada pesanan aktif saat ini.</p>
                </div>
            @endif
        </div>
    </div>

    <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="text-gray-400 hover:text-red-500 p-2 transition focus:outline-none" onclick="return confirm('Keluar dari KantinQuick?')">
            <i class="fa-solid fa-right-from-bracket text-lg"></i>
        </button>
    </form>
</div>
</header>

<main class="p-4 space-y-5">

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
        <div class="mt-6 px-4">
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

    <div class="bg-gray-100 rounded-xl px-4 py-3 flex items-center gap-3 shadow-inner focus-within:ring-2 focus-within:ring-orange-500/20 transition">
        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
        <input type="text" placeholder="Cari stand kantin favoritmu..." class="bg-transparent w-full text-sm focus:outline-none text-gray-700 font-medium">
    </div>

    <div>
        <div class="flex gap-2 overflow-x-auto pb-1 style-scrollbar-none">
            <button onclick="filterMenu('all', this)" class="category-btn bg-orange-500 text-white font-bold text-xs px-4 py-2 rounded-full whitespace-nowrap shadow-sm shadow-orange-500/20">Semua Menu</button>
            <button onclick="filterMenu('makanan', this)" class="category-btn bg-white text-gray-600 font-semibold text-xs px-4 py-2 rounded-full border border-gray-100 whitespace-nowrap hover:bg-orange-50 hover:text-orange-500 transition">Makanan Berat</button>
            <button onclick="filterMenu('cemilan', this)" class="category-btn bg-white text-gray-600 font-semibold text-xs px-4 py-2 rounded-full border border-gray-100 whitespace-nowrap hover:bg-orange-50 hover:text-orange-500 transition">Cemilan & Snack</button>
            <button onclick="filterMenu('minuman', this)" class="category-btn bg-white text-gray-600 font-semibold text-xs px-4 py-2 rounded-full border border-gray-100 whitespace-nowrap hover:bg-orange-50 hover:text-orange-500 transition">Aneka Minuman</button>
        </div>
    </div>

    <div class="flex justify-between items-center pt-2">
        <h2 class="font-extrabold text-base text-gray-900 tracking-tight flex items-center gap-1.5">
            <i class="fa-solid fa-fire text-orange-500 text-sm"></i> Merchant Terpopuler
        </h2>
    </div>

    <div class="space-y-4">
        @foreach($stands as $stand)
            @php
                $imgUrl = $stand->image ? asset('storage/' . $stand->image) : 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500';
            @endphp

            <a href="{{ route('student.stand.detail', $stand->id) }}" class="block bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md hover:border-orange-100 transition duration-200">
                <div class="relative h-36 bg-gray-100">
                    <img src="{{ $imgUrl }}" alt="{{ $stand->stand_name }}" class="w-full h-full object-cover">
                    @if($stand->status == 1)
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
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i> 
                            @php
                                $ratingOtomatis = 4.4 + min(($stand->total_terjual / 5) * 0.1, 0.6);
                            @endphp
                            {{ number_format($ratingOtomatis, 1) }}
                        </span>                  
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</main>

<x-navbar-student active="home" />
<script>
    // 1. Fungsi Dropdown Lonceng Notifikasi
    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Tutup otomatis dropdown jika user klik area luar
    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notificationDropdown');
        const bellButton = dropdown.previousElementSibling;
        if (dropdown && bellButton && !dropdown.contains(e.target) && !bellButton.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // 2. Fungsi Menyaring/Filter Menu Berdasarkan Kategori
    function filterMenu(category, button) {
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('bg-orange-500', 'text-white', 'font-bold', 'shadow-sm', 'shadow-orange-500/20');
            btn.classList.add('bg-white', 'text-gray-600', 'font-semibold', 'border', 'border-gray-100');
        });

        button.classList.remove('bg-white', 'text-gray-600', 'font-semibold', 'border', 'border-gray-100');
        button.classList.add('bg-orange-500', 'text-white', 'font-bold', 'shadow-sm', 'shadow-orange-500/20');

        document.querySelectorAll('.menu-item').forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            if (category === 'all' || itemCategory === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // 3. Fungsi Scroll Halus dan Sorotan Ring Berkedip
    function scrollToMenu(menuId) {
        const targetMenu = document.getElementById(menuId);
        if (targetMenu) {
            const allButton = document.querySelector("button[onclick*='all']");
            if (allButton) filterMenu('all', allButton);

            targetMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
            targetMenu.classList.add('ring-4', 'ring-orange-500', 'scale-105', 'z-10');
            
            setTimeout(() => {
                targetMenu.classList.remove('ring-4', 'ring-orange-500', 'scale-105', 'z-10');
            }, 2000);
        }
    }

    // 4. Fungsi Acak Gacha Kuliner
    // Ambil data menu asli Laravel kamu ke dalam JavaScript secara otomatis
const originalMenus = @json($menus);

let currentAngle = 0;
let isSpinning = false;

// 1. Fungsi Buka Tutup Modal
function openSpinnerModal() {
    document.getElementById('spinnerModal').classList.remove('hidden');
    // Gambar roda sesaat setelah modal terbuka
    setTimeout(drawWheel, 100);
}

function closeSpinnerModal() {
    if (isSpinning) return; // Kunci modal jika roda sedang berputar
    document.getElementById('spinnerModal').classList.add('hidden');
    document.getElementById('gachaResult').classList.add('hidden');
}

// 2. Fungsi Menggambar Roda di Canvas menggunakan nama menu DB
function drawWheel() {
    const canvas = document.getElementById('wheelCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const radius = canvas.width / 2;
    const len = originalMenus.length;
    
    if (len === 0) return;
    const sliceAngle = (2 * Math.PI) / len;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Kumpulan warna estetik pastel kuliner biar kontras
    const colors = ['#FF6B6B', '#FF8E53', '#FFBE53', '#4E65FF', '#92EFFD', '#52E5E7', '#130CB7', '#A0FE65', '#FA709A', '#FEE140'];

    originalMenus.forEach((menu, i) => {
        const startAngle = i * sliceAngle;
        const endAngle = startAngle + sliceAngle;

        // Gambar Potongan Roda
        ctx.beginPath();
        ctx.fillStyle = colors[i % colors.length];
        ctx.moveTo(radius, radius);
        ctx.arc(radius, radius, radius - 2, startAngle, endAngle);
        ctx.fill();
        ctx.stroke();
        ctx.closePath();

        // Tulis Nama Menu di Dalam Potongan
        ctx.save();
        ctx.translate(radius, radius);
        ctx.rotate(startAngle + sliceAngle / 2);
        ctx.fillStyle = "#ffffff";
        ctx.font = "bold 10px sans-serif";
        ctx.shadowColor = "rgba(0, 0, 0, 0.4)";
        ctx.shadowBlur = 4;
        
        // Batasi panjang teks nama menu agar muat di lingkaran
        let textName = menu.name.length > 12 ? menu.name.substr(0, 12) + '..' : menu.name;
        ctx.fillText(textName, radius / 3.5, 5);
        ctx.restore();
    });

    // Gambar lingkaran poros tengah pelengkap estetika
    ctx.beginPath();
    ctx.arc(radius, radius, 20, 0, 2 * Math.PI);
    ctx.fillStyle = '#ffffff';
    ctx.fill();
    ctx.lineWidth = 2;
    ctx.strokeStyle = '#F3F4F6';
    ctx.stroke();
    ctx.closePath();
}

// 3. Fungsi Inti Putar Roda Spinner
function spinTheWheel() {
    if (isSpinning || originalMenus.length === 0) return;

    isSpinning = true;
    document.getElementById('gachaResult').classList.add('hidden');
    document.getElementById('spinButton').disabled = true;
    document.getElementById('spinButton').innerText = "🎰 SEDANG BERPUTAR...";

    const canvas = document.getElementById('wheelCanvas');
    const len = originalMenus.length;
    
    // Tentukan acak putaran minimal 5 kali putar + sudut acak tambahan
    const extraDegrees = Math.floor(Math.random() * 360);
    const totalSpinDegrees = (5 * 360) + extraDegrees;
    
    // Jalankan animasi CSS Transition selama 5 detik
    canvas.style.transform = `rotate(${totalSpinDegrees}deg)`;

    setTimeout(() => {
        // Hitung indeks menu mana yang berhenti tepat di jarum penunjuk atas (270 derajat)
        const sliceAngleDeg = 360 / len;
        const actualStoppedDegrees = (totalSpinDegrees % 360);
        
        // Rumus mencari indeks array berdasarkan sudut stop roda
        let targetIndex = Math.floor((360 - actualStoppedDegrees + 270) / sliceAngleDeg) % len;
        if (targetIndex < 0) targetIndex += len;

        const selectedMenu = originalMenus[targetIndex];

        // Tampilkan Hasilnya dengan Cantik
        document.getElementById('resultMenuName').innerText = selectedMenu.name;
        document.getElementById('resultMenuPrice').innerText = 'Rp ' + Number(selectedMenu.price).toLocaleString('id-ID');
        
        // Pasang link otomatis ke fungsi scroll menu bawaanmu
        document.getElementById('goToMenuBtn').onclick = function() {
            closeSpinnerModal();
            // Scroll otomatis ke section stand menu tersebut berada
            const element = document.getElementById('menu-' + selectedMenu.id);
            if(element) element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        };

        // Reset status tombol
        document.getElementById('gachaResult').classList.remove('hidden');
        document.getElementById('spinButton').disabled = false;
        document.getElementById('spinButton').innerText = "🔥 PUTAR RODA LAGI!";
        isSpinning = false;
        
        // Reset posisi canvas secara instan tanpa transisi agar bisa diputar lagi besok
        canvas.style.transition = 'none';
        canvas.style.transform = `rotate(${actualStoppedDegrees}deg)`;
        setTimeout(() => {
            canvas.style.transition = 'transform 5000ms ease-out';
        }, 50);

    }, 5000); // 5000ms = 5 detik putaran roda
}
</script>

</body>
</html>