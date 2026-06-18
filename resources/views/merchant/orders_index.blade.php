<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin - Kelola Pesanan Merchant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 antialiased max-w-md mx-auto min-h-screen shadow-lg relative pb-20">

    <header class="bg-[#962D15] text-white px-4 py-4 sticky top-0 z-50 shadow-md flex items-center justify-between">
        <div>
            <h1 class="font-black text-lg tracking-wide">Kelola Pesanan Stan</h1>
            <p class="text-[10px] text-orange-200 font-medium uppercase tracking-wider">Mode Penjual / Merchant</p>
        </div>
        <button onclick="window.location.reload()" class="p-2 hover:bg-maroon-700 rounded-xl transition">
            <i class="fa-solid fa-rotate text-lg"></i>
        </button>
    </header>

    <div class="p-4 pb-0">
        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-3 rounded-2xl text-xs font-bold border border-green-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="px-4 pt-3 sticky top-[68px] bg-gray-50 z-40">
        <div class="flex bg-gray-200 p-1 rounded-2xl gap-1">
            <button onclick="switchTab('masuk')" id="btn-masuk" class="tab-btn flex-1 py-2.5 text-xs font-black rounded-xl transition duration-200 bg-[#962D15] text-white shadow-sm">
                Masuk ({{ $orderMasuk->count() }})
            </button>
            <button onclick="switchTab('dimasak')" id="btn-dimasak" class="tab-btn flex-1 py-2.5 text-xs font-black rounded-xl transition duration-200 text-gray-500 hover:text-gray-800">
                Dimasak ({{ $sedangDimasak->count() }})
            </button>
            <button onclick="switchTab('selesai')" id="btn-selesai" class="tab-btn flex-1 py-2.5 text-xs font-black rounded-xl transition duration-200 text-gray-500 hover:text-gray-800">
                Selesai ({{ $orderSelesai->count() }})
            </button>
        </div>
    </div>

    <main class="p-4">
        
        <div id="tab-content-masuk" class="tab-content space-y-4">
            @if($orderMasuk->isEmpty())
                @include('merchant.partials.empty_state', ['msg' => 'Belum ada pesanan masuk dari mahasiswa nih.'])
            @else
                @foreach($orderMasuk as $order)
                    @include('merchant.partials.order_card', ['order' => $order, 'action' => 'dimasak', 'btnText' => 'Mulai Masak', 'color' => 'bg-orange-500 hover:bg-orange-600'])
                @endforeach
            @endif
        </div>

        <div id="tab-content-dimasak" class="tab-content hidden space-y-4">
            @if($sedangDimasak->isEmpty())
                @include('merchant.partials.empty_state', ['msg' => 'Dapur tenang. Tidak ada makanan yang sedang dimasak.'])
            @else
                @foreach($sedangDimasak as $order)
                    @include('merchant.partials.order_card', ['order' => $order, 'action' => 'siap diambil', 'btnText' => 'Siap Diambil', 'color' => 'bg-blue-600 hover:bg-blue-700'])
                @endforeach
            @endif
        </div>

        <div id="tab-content-selesai" class="tab-content hidden space-y-4">
            @if($orderSelesai->isEmpty())
                @include('merchant.partials.empty_state', ['msg' => 'Belum ada riwayat transaksi selesai hari ini.'])
            @else
                @foreach($orderSelesai as $order)
                    @include('merchant.partials.order_card', ['order' => $order, 'action' => 'selesai', 'btnText' => 'Selesai', 'color' => ''])
                @endforeach
            @endif
        </div>

    </main>

    <script>
        function switchTab(tabName) {
            // Sembunyikan semua konten tab
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Tampilkan tab yang dipilih
            document.getElementById('tab-content-' + tabName).classList.remove('hidden');

            // Reset semua style tombol tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-[#962D15]', 'text-white', 'shadow-sm');
                btn.classList.add('text-gray-500');
            });

            // Beri highlight aktif pada tombol yang diklik
            const activeBtn = document.getElementById('btn-' + tabName);
            activeBtn.classList.add('bg-[#962D15]', 'text-white', 'shadow-sm');
            activeBtn.classList.remove('text-gray-500');
        }
    </script>
</body>
</html>