<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin - Merchant Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#1A1A1A] text-gray-100 antialiased max-w-md mx-auto min-h-screen flex flex-col justify-center px-6 py-8 shadow-2xl relative">

    <div class="w-full text-center mb-6">
        <div class="text-amber-500 text-5xl mb-3">
            <i class="fa-solid fa-store-slash fa-store text-[#FF5722]"></i>
        </div>
        <h1 class="text-3xl font-bold text-white tracking-wide">Merchant Portal</h1>
        <p class="text-xs text-gray-400 mt-1 tracking-wide">Khusus Pengelola & Pemilik Stand Kantin</p>
    </div>

    <div class="bg-[#262626] p-6 rounded-3xl shadow-xl border border-neutral-800">
        
        @if (session('error'))
            <div class="bg-red-950/50 text-red-400 p-3.5 rounded-xl text-xs font-bold mb-4 border border-red-900 flex items-start gap-2">
                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('merchant.login.submit') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold text-xs text-gray-300 mb-1.5 uppercase tracking-wider">Email Resmi Stand</label>
                <div class="bg-[#333333] rounded-xl px-4 py-3 flex items-center gap-3 border border-transparent focus-within:border-amber-500 transition">
                    <i class="fa-regular fa-envelope text-gray-500 text-lg"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@merchant.com" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-600 text-white" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block font-semibold text-xs text-gray-300 mb-1.5 uppercase tracking-wider">Password</label>
                <div class="bg-[#333333] rounded-xl px-4 py-3 flex items-center gap-3 border border-transparent focus-within:border-amber-500 transition">
                    <i class="fa-solid fa-lock text-gray-500 text-lg"></i>
                    <input type="password" name="password" placeholder="••••••••" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-600 text-white" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#FF5722] hover:bg-orange-600 text-white font-bold py-3.5 rounded-full shadow-lg transition tracking-wide text-sm mb-4">
                Masuk Dashboard
            </button>

            <p class="text-center text-xs text-gray-500">
                Bukan pemilik stand? 
                <a href="{{ route('login') }}" class="text-amber-500 font-bold hover:underline ml-0.5">Login Mahasiswa</a>
            </p>
        </form>
    </div>

    <footer class="text-center text-[10px] text-neutral-600 mt-8">
        &copy; 2026 UnilamKantin Secure Merchant Core.
    </footer>

</body>
</html>