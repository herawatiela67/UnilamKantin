<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin - Profil Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased max-w-md mx-auto min-h-screen flex flex-col pb-24 shadow-lg bg-white relative">

    <header class="bg-white px-4 py-3 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <a href="{{ route('student.home') }}" class="text-gray-700 p-1 hover:text-orange-500 transition">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h1 class="font-bold text-base text-gray-900">Profil Pengguna</h1>
    </header>

    <main class="p-5 flex-1 space-y-6">
        <div class="text-center space-y-2 py-4">
            <div class="w-20 h-20 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-3xl font-black mx-auto shadow-sm border border-orange-200">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-black text-lg text-gray-900 leading-tight">{{ $user->name }}</h2>
                <p class="text-xs text-gray-400 font-medium">{{ $user->email }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm space-y-3.5">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider border-b pb-2 mb-1">
                <i class="fa-solid fa-circle-user text-orange-500 mr-1"></i> Informasi Pribadi
            </h3>
            
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-400">Nama Lengkap</span>
                <span class="font-bold text-gray-800">{{ $user->name }}</span>
            </div>
            
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-400">Status Akun</span>
                
                @if(strtolower($user->role ?? 'student') === 'merchant')
                    <span class="text-[10px] font-extrabold bg-amber-50 text-amber-600 px-2 py-0.5 rounded-md uppercase tracking-wide border border-amber-100">
                        Pedagang / Merchant
                    </span>
                @else
                    <span class="text-[10px] font-extrabold bg-green-50 text-green-600 px-2 py-0.5 rounded-md uppercase tracking-wide border border-green-100">
                        Mahasiswa
                    </span>
                @endif
            </div>
        </div>

        <div class="pt-4">
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin keluar dari akun KantinQuick?')">
                @csrf
                <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-3 rounded-xl border border-red-200 text-center text-sm transition tracking-wide flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                    <i class="fa-solid fa-power-off text-base"></i> Keluar / Log Out
                </button>
            </form>
        </div>
    </main>

    <x-navbar-student active="profile" />

</body>
</html>