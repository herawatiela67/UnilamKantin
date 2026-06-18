<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin - Login Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#F5F5F5] text-gray-800 antialiased max-w-md mx-auto min-h-screen flex flex-col justify-center px-6 py-8 shadow-lg relative">

    <div class="w-full text-center mb-6">
        <div class="text-[#C0392B] text-5xl mb-3">
            <i class="fa-solid fa-utensils"></i>
        </div>

        <h1 class="text-3xl font-bold text-[#962D15]">UnilamKantin</h1>
        <p class="text-sm text-gray-400 mt-1 tracking-wide">Campus Dining, Simplified.</p>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        
        <div class="bg-[#E3F2FD] text-blue-600 font-bold text-xs text-center py-3 rounded-full tracking-wider mb-6">
            🔐 GERBANG MASUK MAHASISWA
        </div>

        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-3.5 rounded-xl text-xs font-bold mb-4 border border-red-100 flex items-start gap-2">
                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold text-sm text-gray-700 mb-1.5">NIM / Email</label>
                <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3 border border-transparent focus-within:border-gray-300 transition">
                    <i class="fa-regular fa-user text-gray-400 text-lg"></i>
                    <input type="text" name="login_identifier" value="{{ old('login_identifier') }}" placeholder="Cari NIM atau email" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400" required>
                </div>
            </div>

            <div class="mb-2">
                <label class="block font-semibold text-sm text-gray-700 mb-1.5">Password</label>
                <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3 border border-transparent focus-within:border-gray-300 transition relative">
                    <i class="fa-solid fa-lock text-gray-400 text-lg"></i>
                    <input type="password" id="passwordInput" name="password" placeholder="••••••••" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400" required>
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 text-gray-400 hover:text-gray-600">
                        <i id="passwordIcon" class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="text-right mb-5">
                <a href="#" class="text-[#962D15] text-xs font-medium hover:underline">Lupa Password?</a>
            </div>

            <button type="submit" class="w-full bg-[#FF5722] hover:bg-orange-600 text-white font-bold py-3.5 rounded-full shadow-md hover:shadow-lg transition tracking-wide text-sm mb-4">
                Masuk Sebagai Mahasiswa
            </button>

            <p class="text-center text-xs text-gray-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-[#962D15] font-bold hover:underline ml-0.5">Daftar di sini</a>
            </p>
        </form>
    </div>

   <div class="text-center mt-5 text-xs text-gray-500">
    Pemilik Stand Kantin? 
    <a href="{{ route('merchant.login') }}" class="text-[#962D15] font-bold underline ml-0.5 hover:text-red-800">Masuk Lewat Sini</a>
</div>

    <div class="grid grid-cols-2 gap-4 mt-6">
        <div class="h-24 rounded-xl overflow-hidden bg-gray-200">
            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400" class="w-full h-full object-cover">
        </div>
        <div class="h-24 rounded-xl overflow-hidden bg-gray-200">
            <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400" class="w-full h-full object-cover">
        </div>
    </div>

    <footer class="text-center text-[11px] text-gray-400 mt-8">
        &copy; 2026 UnilamKantin. Built for Speed.
    </footer>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>