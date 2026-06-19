<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pesanan Stand - UnilamKantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
<audio id="notifAlarm" loop>
    <source src="https://assets.mixkit.co/active_storage/sfx/911/911-84.wav" type="audio/wav">
    <source src="https://www.soundjay.com/buttons/sounds/beep-06.mp3" type="audio/mp3">
</audio>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F5F5F5] text-gray-800 antialiased max-w-md mx-auto min-h-screen flex flex-col pb-24 shadow-lg bg-white relative">

    <div class="bg-[#962D15] p-5 text-white rounded-b-3xl shadow-md sticky top-0 z-50">
        <div class="flex justify-between items-center mb-4">
            <div>
                <span class="text-xs opacity-75 font-medium">Selamat Datang, Pemilik Stand</span>
                <h1 class="text-xl font-bold tracking-wide">{{ Auth::user()->name }}</h1>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-white/20 hover:bg-white/30 text-white p-2.5 rounded-full text-xs transition">
                    <i class="fa-solid fa-power-off text-base"></i>
                </button>
            </form>
        </div>
   
        <div class="bg-white/10 p-3.5 rounded-2xl flex items-center justify-between border border-white/10">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-store text-2xl text-amber-400"></i>
                <div>
                    <h2 class="text-sm font-bold">{{ $stand->stand_name ?? 'Stand Belum Terdaftar' }}</h2>
                    <p class="text-[11px] opacity-80">Stand: {{ $stand->stand_number ?? '-' }}</p>
                </div>
            </div>
    
            <form action="{{ route('merchant.stand.toggleStatus') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-wider transition duration-200 cursor-pointer shadow-sm
                    {{ $stand->status == 1 ? 'bg-emerald-500 text-white hover:bg-emerald-600' : 'bg-gray-400 text-white hover:bg-gray-500' }}">
                    <i class="fa-solid {{ $stand->status == 1 ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                    <span>{{ $stand->status == 1 ? 'Buka' : 'Tutup' }}</span>
                </button>
            </form>
        </div>
 </div>
    @if(session('success'))
        <div class="m-4 bg-green-50 text-green-600 p-3.5 rounded-xl text-xs font-bold border border-green-100 flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="p-4 flex-1 space-y-4">
        <div class="flex bg-gray-100 p-1 rounded-xl gap-1 mb-2 text-[11px]">
            <button onclick="switchSubTab('masuk')" id="btn-sub-masuk" class="sub-tab-btn flex-1 py-2 font-bold rounded-lg transition bg-white text-gray-800 shadow-sm">
                Masuk ({{ $orderMasuk->count() }})
            </button>
            <button onclick="switchSubTab('dimasak')" id="btn-sub-dimasak" class="sub-tab-btn flex-1 py-2 font-bold rounded-lg transition text-gray-500">
                Dimasak ({{ $sedangDimasak->count() }})
            </button>
            <button onclick="switchSubTab('selesai')" id="btn-sub-selesai" class="sub-tab-btn flex-1 py-2 font-bold rounded-lg transition text-gray-500">
                Selesai ({{ $orderSelesai->count() }})
            </button>
        </div>

        <div id="sub-content-masuk" class="sub-tab-content space-y-3">
            @if($orderMasuk->isEmpty())
                <div class="text-center py-12 text-gray-300"><i class="fa-solid fa-mortar-pestle text-4xl mb-2"></i><p class="text-xs font-medium text-gray-400">Belum ada pesanan masuk.</p></div>
            @else
                @foreach($orderMasuk as $order)
                    @include('merchant.partials.order_card', ['order' => $order, 'action' => 'dimasak', 'btnText' => 'Mulai Masak', 'color' => 'bg-orange-500 hover:bg-orange-600'])
                @endforeach
            @endif
        </div>

        <div id="sub-content-dimasak" class="sub-tab-content hidden space-y-3">
            @if($sedangDimasak->isEmpty())
                <div class="text-center py-12 text-gray-300"><i class="fa-solid fa-fire-burner text-4xl mb-2"></i><p class="text-xs font-medium text-gray-400">Tidak ada masakan aktif.</p></div>
            @else
                @foreach($sedangDimasak as $order)
                    @include('merchant.partials.order_card', ['order' => $order, 'action' => 'siap diambil', 'btnText' => 'Siap Diambil', 'color' => 'bg-blue-600 hover:bg-blue-700'])
                @endforeach
            @endif
        </div>

        <div id="sub-content-selesai" class="sub-tab-content hidden space-y-3">
            @if($orderSelesai->isEmpty())
                <div class="text-center py-12 text-gray-300"><i class="fa-solid fa-circle-check text-4xl mb-2"></i><p class="text-xs font-medium text-gray-400">Belum ada pesanan selesai.</p></div>
            @else
                @foreach($orderSelesai as $order)
                    @include('merchant.partials.order_card', ['order' => $order, 'action' => 'selesai', 'btnText' => 'Selesai', 'color' => ''])
                @endforeach
            @endif
        </div>
    </div>

    <div class="fixed bottom-0 max-w-md w-full bg-white border-t border-gray-100 shadow-lg z-50 left-0 right-0 mx-auto">
        <nav class="grid grid-cols-2 w-full h-16 bg-white">
            <a href="{{ route('merchant.home') }}" class="flex flex-col items-center justify-center text-[#962D15] transition">
                <i class="fa-solid fa-receipt text-xl"></i>
                <span class="text-[10px] font-black mt-1">Pesanan</span>
            </a>
            <a href="{{ route('merchant.menu.index') }}" class="flex flex-col items-center justify-center text-gray-400 hover:text-orange-500 transition">
                <i class="fa-solid fa-utensils text-xl"></i>
                <span class="text-[10px] font-medium mt-1">Menu Stand</span>
            </a>
        </nav>
    </div>

    <script>
        function switchSubTab(statusName) {
            document.querySelectorAll('.sub-tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('sub-content-' + statusName).classList.remove('hidden');
            document.querySelectorAll('.sub-tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-gray-800', 'shadow-sm');
                btn.classList.add('text-gray-500');
            });
            const activeSubBtn = document.getElementById('btn-sub-' + statusName);
            activeSubBtn.classList.add('bg-white', 'text-gray-800', 'shadow-sm');
            activeSubBtn.classList.remove('text-gray-500');
        }

        let alarm = document.getElementById('notifAlarm');
        let alarmMuted = false;

        if (alarm) {
            alarm.volume = 1.0; // Paksa volume maksimal suara laptop
        }

        function cekPesananMasuk() {
            fetch("{{ route('merchant.check.new.orders') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.adaPesananBaru) {
                        // Bunyikan alarm sirene jika belum di-mute sementara
                        if (!alarmMuted) {
                            if (alarm) {
                                alarm.play().catch(err => console.log("Menunggu interaksi pertama klik..."));
                            }
                        }
                        
                        // Opsional: Tampilkan alert/notif teks jumlah pesanan di layar dashboard
                        console.log("📢 Ada " + data.jumlahPesanan + " pesanan masuk, El! Segera masak!");
                    } else {
                        // Kalau semua pesanan berstatus pending/masuk sudah diklik "Terima" atau "Dimasak"
                        // Maka matikan alarm secara otomatis agar tidak bising lagi
                        if (alarm) {
                            alarm.pause();
                            alarm.currentTime = 0;
                        }
                        alarmMuted = false; // Reset status mute untuk pesanan berikutnya nanti
                    }
                })
                .catch(error => console.error('Gagal memuat status pesanan:', error));
        }

        // Cek otomatis setiap 10 detik non-stop
        setInterval(cekPesananMasuk, 10000);

        // Pancingan klik pertama agar browser mengizinkan keluar suara
        document.addEventListener('click', function() {
            console.log("Sistem audio stand diaktifkan!");
        }, { once: true });
    </script>
</body>
</html>