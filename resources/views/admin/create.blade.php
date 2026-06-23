<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah Stan Kantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded-2xl shadow-md border border-gray-100">
        <div class="flex items-center gap-3 border-b pb-4 mb-6">
            <a href="{{ route('admin.stands.index') }}" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left text-lg"></i></a>
            <h1 class="text-lg font-bold text-gray-900">Tambah Stan Kuliner Baru</h1>
        </div>

       <form action="{{ route('admin.stands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- SECTION 1: PEMBUATAN AKUN PEDAGANG BARU -->
    <div class="p-4 bg-gray-50 border border-gray-100 rounded-2xl space-y-4">
        <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider border-b border-gray-200 pb-2 flex items-center gap-2">
            <i class="fa-solid fa-user-plus text-orange-500"></i> Data Akun Pedagang Baru
        </h3>
        
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap Pemilik</label>
            <input type="text" name="owner_name" required placeholder="Contoh: Ela Herawati" class="w-full p-3 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:border-orange-500">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email untuk Login</label>
                <input type="email" name="owner_email" required placeholder="Contoh: ela@kantin.com" class="w-full p-3 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:border-orange-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password Akun</label>
                <input type="password" name="owner_password" required placeholder="Minimal 6 karakter" class="w-full p-3 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:border-orange-500">
            </div>
        </div>
    </div>

    <!-- SECTION 2: DETAIL DATA STAN KULINER -->
    <div class="space-y-4">
        <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider border-b border-gray-200 pb-2 flex items-center gap-2">
            <i class="fa-solid fa-store text-orange-500"></i> Informasi Stan Kuliner
        </h3>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Stan Kuliner</label>
            <input type="text" name="stand_name" required placeholder="Contoh: Ayam Geprek Hot" class="w-full p-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-orange-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nomor Lapak</label>
            <input type="text" name="stand_number" required placeholder="Contoh: Lapak No. 1" class="w-full p-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-orange-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Foto Banner Stan</label>
            <input type="file" name="image" accept="image/*" class="w-full p-2.5 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
        </div>
    </div>

    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl shadow-lg transition text-sm tracking-wide mt-4">
        <i class="fa-solid fa-circle-check mr-1"></i> Daftarkan Pedagang Baru
    </button>
</form>
    </div>

</body>
</html>