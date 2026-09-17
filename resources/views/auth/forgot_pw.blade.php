<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>UnilamKantin - Lupa Password</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="bg-[#f5f5f5] antialiased w-full min-h-screen flex flex-col justify-center px-6 py-8 shadow-lg relative">

        <div class="w-full text-center mb-6">
            <div class="text-[#C0392b] text-5xl mb-3">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <h1 class="text-3xl font-bold text-[#962D15]"> UnilamKantin</h1>
            <p class="text-sm text-gray-400 mt-1 tracking-wide"> Universitas La Tansa Mashiro</p>
        </div>

        <div class="bg-[#FFF3E0] text-[#e65100] font-bold text-xs text-center py-3 rounded-full tracking-wider mb-6">
            🔑PULIHKAN KATA SANDI MAHASISWA
        </div>

        @if (session('status'))
    <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px; margin-bottom: 15px; border-radius: 5px;">
        {{ session('status') }}
    </div>
@endif
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                Masukkan email terdaftar kamu. Kami akan mengirimkan tautan khusus untuk mengatur ulang kata sandi akunmu.
            </p>

            <form action="{{ route('password.email') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-5">
                    <label class="block font-semibold text-sm text-gray-700 mb-1.5"> Email Mahasiswa</label>
                    <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3 border border-transparent focus-within:border-gray-300 transition">
                        <i class="fa-reguler fa-enveloper text-gray-400 text-lg"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="hera@mahasiswa.com" class="bg-transparent w-full text-sm focus:outline-none placeholder-gray-400" required autofocus>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#FF5722] hover:bg-orange-600 text-white font-bold py-3.5 rounded-full shadow-md hover:shadow-lg transition tracking-wide text-sm mb-3">
                    Kirim Link Reset Password
                </button>
            </form>
        </div>

        <div class="text-center text-xs text-gray-500">
        Sudah Ingat Password?
        <a href="{{ route('login') }}" class="text-[#962D15] font-bold hover:underline ml-0.5">
            <i class="fa-solid fa-arrow-left mr-1"></i>Kembali ke login
        </a>
    </div>    
    
    </body>
</html>