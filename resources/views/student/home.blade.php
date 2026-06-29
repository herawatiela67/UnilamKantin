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

@include('student.partials.header')

<main class="p-4 space-y-5">

    @include('student.partials.gacha')

    <div class="space-y-4">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" id="searchStandInput" onkeyup="filterStands()" placeholder="Cari stand kuliner favoritmu..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition">
        </div>
    </div>

    @include('student.partials.active_orders')

   <div>
    <div class="flex gap-2 overflow-x-auto pb-1 style-scrollbar-none">
    <button onclick="filterStand('all', this)" class="category-btn bg-orange-500 text-white font-bold text-xs px-4 py-2 rounded-full whitespace-nowrap shadow-sm shadow-orange-500/20">Semua Stan</button>
    <button onclick="filterStand('Makanan Berat', this)" class="category-btn bg-white text-gray-600 font-semibold text-xs px-4 py-2 rounded-full border border-gray-100 whitespace-nowrap hover:bg-orange-50 hover:text-orange-500 transition">Stan Makanan</button>
    <button onclick="filterStand('cemilan', this)" class="category-btn bg-white text-gray-600 font-semibold text-xs px-4 py-2 rounded-full border border-gray-100 whitespace-nowrap hover:bg-orange-50 hover:text-orange-500 transition">Stan Cemilan</button>
    <button onclick="filterStand('minuman', this)" class="category-btn bg-white text-gray-600 font-semibold text-xs px-4 py-2 rounded-full border border-gray-100 whitespace-nowrap hover:bg-orange-50 hover:text-orange-500 transition">Stan Minuman</button>
</div>
</div>

    <div class="flex justify-between items-center pt-2">
        <h2 class="font-extrabold text-base text-gray-900 tracking-tight flex items-center gap-1.5">
            <i class="fa-solid fa-fire text-orange-500 text-sm"></i> Merchant Terpopuler
        </h2>
    </div>

    @include('student.partials.merchant_list')

</main>

<x-navbar-student active="home" />

<script>
    // Variable global penyimpan status filter aktif
    let currentCategory = 'all';

    // LOGIKA FILTER UTAMA (GABUNGAN SEARCH + BUTTON KATEGORI)
    function jalankanFilterGabungan() {
    const searchInput = document.getElementById('searchStandInput').value.toLowerCase().trim();
    const standCards = document.querySelectorAll('.stand-card');
    let foundCount = 0;

    standCards.forEach(card => {
        // Ambil data dari atribut HTML, paksa ke huruf kecil
        const standName = (card.getAttribute('data-name') || '').toLowerCase().trim();
        const standCategory = (card.getAttribute('data-category') || '').toLowerCase().trim();

        // Logika pencocokan yang super ketat tapi aman
        const cocokKategori = (currentCategory === 'all' || standCategory === currentCategory.toLowerCase().trim());
        const cocokNama = standName.includes(searchInput);

        // Debugging otomatis ke Console F12 Firefox kamu
        console.log(`Stan: ${standName} | Kategori DB: ${standCategory} | Filter Aktif: ${currentCategory} | Cocok? ${cocokKategori}`);

        if (cocokKategori && cocokNama) {
            card.style.setProperty('display', 'block', 'important'); 
            foundCount++;
        } else {
            card.style.setProperty('display', 'none', 'important');
        }
    });

    // Notifikasi jika kosong
    const noResultElement = document.getElementById('noStandFound');
    if (noResultElement) {
        noResultElement.style.setProperty('display', foundCount === 0 ? 'block' : 'none', 'important');
    }
}

    // Pemicu Filter saat Tombol Kategori diklik
    function filterStand(category, button) {
        currentCategory = category;

        const buttons = document.querySelectorAll('.category-btn');
        buttons.forEach(btn => {
            btn.className = "category-btn bg-white text-gray-600 font-semibold text-xs px-4 py-2 rounded-full border border-gray-100 whitespace-nowrap hover:bg-orange-50 hover:text-orange-500 transition";
        });
        button.className = "category-btn bg-orange-500 text-white font-bold text-xs px-4 py-2 rounded-full whitespace-nowrap shadow-sm shadow-orange-500/20";

        jalankanFilterGabungan();
    }

    // Pemicu Filter saat Kolom Pencarian di-ketik
    function filterStands() {
        jalankanFilterGabungan();
    }

    // --- FITUR GACHA RODA KULINER BAWAAN KAMU ---
    const originalMenus = @json($menus);
    let currentAngle = 0;
    let isSpinning = false;

    function openSpinnerModal() {
        document.getElementById('spinnerModal').classList.remove('hidden');
        setTimeout(drawWheel, 100);
    }

    // Fungsi penutup modal gacha
    function closeSpinnerModal() {
        if (isSpinning) return;
        document.getElementById('spinnerModal').classList.add('hidden');
        document.getElementById('gachaResult').classList.add('hidden');
    }

    // Menggambar lingkar roda gacha memakai Canvas HTML5
    function drawWheel() { 
        const canvas = document.getElementById('wheelCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const radius = canvas.width / 2;
        const len = originalMenus.length;
        
        if (len === 0) return;
        const sliceAngle = (2 * Math.PI) / len;

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const colors = ['#FF6B6B', '#FF8E53', '#FFBE53', '#4E65FF', '#92EFFD', '#52E5E7', '#130CB7', '#A0FE65', '#FA709A', '#FEE140'];

        originalMenus.forEach((menu, i) => {
            const startAngle = i * sliceAngle;
            const endAngle = startAngle + sliceAngle;

            ctx.beginPath();
            ctx.fillStyle = colors[i % colors.length];
            ctx.moveTo(radius, radius);
            ctx.arc(radius, radius, radius - 2, startAngle, endAngle);
            ctx.fill();
            ctx.stroke();
            ctx.closePath();

            ctx.save();
            ctx.translate(radius, radius);
            ctx.rotate(startAngle + sliceAngle / 2);
            ctx.fillStyle = "#ffffff";
            ctx.font = "bold 10px sans-serif";
            
            let textName = menu.name.length > 12 ? menu.name.substr(0, 12) + '..' : menu.name;
            ctx.fillText(textName, radius / 3.5, 5);
            ctx.restore();
        });

        ctx.beginPath();
        ctx.arc(radius, radius, 20, 0, 2 * Math.PI);
        ctx.fillStyle = '#ffffff';
        ctx.fill();
        ctx.lineWidth = 2;
        ctx.strokeStyle = '#F3F4F6';
        ctx.stroke();
        ctx.closePath();
    }

    // Proses memutar roda gacha secara random acak
    function spinTheWheel() {
        if (isSpinning || originalMenus.length === 0) return;

        isSpinning = true;
        document.getElementById('gachaResult').classList.add('hidden');
        document.getElementById('spinButton').disabled = true;
        document.getElementById('spinButton').innerText = "🎰 SEDANG BERPUTAR...";

        const canvas = document.getElementById('wheelCanvas');
        const len = originalMenus.length;
        
        const extraDegrees = Math.floor(Math.random() * 360);
        const totalSpinDegrees = (5 * 360) + extraDegrees;
        
        canvas.style.transform = `rotate(${totalSpinDegrees}deg)`;

        setTimeout(() => {
            const sliceAngleDeg = 360 / len;
            const actualStoppedDegrees = (totalSpinDegrees % 360);
            
            let targetIndex = Math.floor((360 - actualStoppedDegrees + 270) / sliceAngleDeg) % len;
            if (targetIndex < 0) targetIndex += len;

            const selectedMenu = originalMenus[targetIndex];

            document.getElementById('resultMenuName').innerText = selectedMenu.name;
            document.getElementById('resultMenuPrice').innerText = 'Rp ' + Number(selectedMenu.price).toLocaleString('id-ID');
            
            document.getElementById('goToMenuBtn').onclick = function() {
                closeSpinnerModal();
                let standUrl = "{{ route('student.stand.detail', ':id') }}"; 
                standUrl = standUrl.replace(':id', selectedMenu.stand_id);
                window.location.href = standUrl;
            };

            document.getElementById('gachaResult').classList.remove('hidden');
            document.getElementById('spinButton').disabled = false;
            document.getElementById('spinButton').innerText = "🔥 PUTAR RODA LAGI!";
            isSpinning = false;
            
            canvas.style.transition = 'none';
            canvas.style.transform = `rotate(${actualStoppedDegrees}deg)`;
            setTimeout(() => {
                canvas.style.transition = 'transform 5000ms ease-out';
            }, 50);

        }, 5000);
    }

    // --- LOGIKA REAL-TIME COUNT NOTIFIKASI MAHASISWA ---
    function cekNotifikasiBaru() {
        fetch("{{ route('customer.check.notifications') }}")
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notif-badge');
                if (badge) {
                    if (data.unreadCount > 0) {
                        badge.innerText = data.unreadCount;
                        badge.classList.remove('hidden'); 
                    } else {
                        badge.classList.add('hidden'); 
                    }
                }
            })
            .catch(error => console.error('Gagal memuat notifikasi mahasiswa:', error));
    }

    // Interval hitung berkala per 5 detik sekali
    setInterval(cekNotifikasiBaru, 5000);
    document.addEventListener('DOMContentLoaded', cekNotifikasiBaru);
</script>
</body>
</html>