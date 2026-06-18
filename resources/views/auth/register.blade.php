<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin - Daftar Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#F5F5F5] text-gray-800 antialiased max-w-md mx-auto min-h-screen flex flex-col justify-center px-6 py-8 shadow-lg relative">

    <div class="w-full text-center mb-6">
        <div class="text-[#C0392B] text-5xl mb-3">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <h1 class="text-3xl font-bold text-[#962D15]">Daftar Akun</h1>
        <p class="text-sm text-gray-400 mt-1 tracking-wide">Gabung UnilamKantin sekarang.</p>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3.5 rounded-xl text-xs font-bold mb-4 border border-red-100 space-y-1">
                @foreach ($errors->all() as $error)
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold text-sm text-gray-700 mb-1.5">Nama Lengkap</label>
                <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3 border border-transparent focus-within:border-gray-300 transition">
                    <i class="fa-regular fa-id-card text-gray-400 text-lg"></i>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block font-semibold text-sm text-gray-700 mb-1.5">NIM / Email</label>
                <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3 border border-transparent focus-within:border-gray-300 transition">
                    <i class="fa-regular fa-envelope text-gray-400 text-lg"></i>
                    <input type="text" name="email" value="{{ old('email') }}" placeholder="Masukkan NIM atau Email" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block font-semibold text-sm text-gray-700 mb-1.5">Password</label>
                <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3 border border-transparent focus-within:border-gray-300 transition">
                    <i class="fa-solid fa-lock text-gray-400 text-lg"></i>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block font-semibold text-sm text-gray-700 mb-1.5">Ulangi Password</label>
                <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3 border border-transparent focus-within:border-gray-300 transition">
                    <i class="fa-solid fa-shield-halved text-gray-400 text-lg"></i>
                    <input type="password" name="password_confirmation" placeholder="••••••••" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#FF5722] hover:bg-orange-600 text-white font-bold py-3.5 rounded-full shadow-md hover:shadow-lg transition tracking-wide text-sm mb-4">
                Daftar Sekarang
            </button>

            <p class="text-center text-xs text-gray-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-[#962D15] font-bold hover:underline ml-0.5">Masuk di sini</a>
            </p>
        </form>
    </div>

    <footer class="text-center text-[11px] text-gray-400 mt-8">
        &copy; 2026 UnilamKantin. Built for Speed.
    </footer>

</body>
</html>