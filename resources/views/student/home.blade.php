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

    @include('student.partials.merchant_list')

</main>

<x-navbar-student active="home" />

<script>
    function filterMenu() {
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
    function scrollToMenu() {
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
    const originalMenus = @json($menus);

    let currentAngle = 0;
    let isSpinning = false;

    function openSpinnerModal() {
        document.getElementById('spinnerModal').classList.remove('hidden');
        setTimeout(drawWheel, 100);
    }

    function closeSpinnerModal() {
        if (isSpinning) return;
        document.getElementById('spinnerModal').classList.add('hidden');
        document.getElementById('gachaResult').classList.add('hidden');
    }
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
            ctx.shadowColor = "rgba(0, 0, 0, 0.4)";
            ctx.shadowBlur = 4;
            
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
    function filterStands() {
        const searchInput = document.getElementById('searchStandInput').value.toLowerCase();
        const standCards = document.querySelectorAll('.stand-card');
        let foundCount = 0;

        standCards.forEach(card => {
            const standName = card.getAttribute('data-name');
            if (standName.includes(searchInput)) {
                card.classList.remove('hidden');
                foundCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        const noResultElement = document.getElementById('noStandFound');
        if (foundCount === 0) {
            noResultElement.classList.remove('hidden');
        } else {
            noResultElement.classList.add('hidden');
        }
     }

    function cekNotifikasiBaru() {
        // Tembak route penampung count notifikasi secara real-time
        fetch("{{ route('customer.check.notifications') }}")
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notif-badge');
                
                if (data.unreadCount > 0) {
                    // Isi angka notifikasi secara real-time
                    badge.innerText = data.unreadCount;
                    // Munculkan bulatan merah jika ada notifikasi aktif
                    badge.classList.remove('hidden'); 
                } else {
                    // Sembunyikan bulatan jika tidak ada notifikasi (0)
                    badge.classList.add('hidden'); 
                }
            })
            .catch(error => console.error('Gagal memuat notifikasi mahasiswa:', error));
    }

    // Jalankan otomatis setiap 5 detik non-stop biar responsif, El!
    setInterval(cekNotifikasiBaru, 5000);
    
    // Jalankan sekali di awal saat halaman berhasil dimuat pertama kali
    document.addEventListener('DOMContentLoaded', cekNotifikasiBaru);
</script>

</body>
</html>