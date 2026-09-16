<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>UnilamKantin - Buat Password Baru</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="bg-[#f5f5f5] antialiased w-full min-h-screen flex flex-col justify-center px-6 py-8 shadow-lg relative">
        <div class="w-full text-center mb-6">
            <div class="text-[#C0392B] text-5xl mb-3">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h1 class="text-3xl font-bold text-[#963D15]">UnilamKantin</h1>
            <p class="text-sm text-gray-400 mt-1 tracking-wide">Universitas La Tansa Mashiro</p>

        </div>

        <div class="bg-[#FFF3E0] text-[#E65100] font-bold text-xs text-center py-3 rounded-full tracking-wider mb-6">
        🔒 BUAT KATA SANDI BARU
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('password.update') }}" method="POST" autocomplete="off">
            @csrf
            
            <!-- Token rahasia wajib disisipkan -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-4">
                <label class="block font-semibold text-sm text-gray-700 mb-1.5">Email Mahasiswa</label>
                <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3">
                    <i class="fa-regular fa-envelope text-gray-400 text-lg"></i>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" class="bg-transparent w-full text-sm focus:outline-none" required readonly>
                </div>
            </div>

            <div class="mb-4">
                <label class="block font-semibold text-sm text-gray-700 mb-1.5">Password Baru</label>
                <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3">
                    <i class="fa-solid fa-lock text-gray-400 text-lg"></i>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" class="bg-transparent w-full text-sm focus:outline-none" required autofocus>
                </div>
            </div>

            <div class="mb-6">
                <label class="block font-semibold text-sm text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                <div class="bg-[#F0EDED] rounded-xl px-4 py-3 flex items-center gap-3">
                    <i class="fa-solid fa-lock text-gray-400 text-lg"></i>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="bg-transparent w-full text-sm focus:outline-none" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#FF5722] hover:bg-orange-600 text-white font-bold py-3.5 rounded-full shadow-md hover:shadow-lg transition tracking-wide text-sm">
                Simpan Password Baru
            </button>
        </form>
    </div>


</html>