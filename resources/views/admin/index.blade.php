<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stan - KantinQuick</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    <span class="p-2 bg-orange-500 text-white rounded-xl shadow-md shadow-orange-200">
                        <i class="fa-solid fa-store text-sm"></i>
                    </span>
                    Manajemen Stan Kuliner
                </h1>
                <p class="text-sm text-gray-500 mt-1">Kelola data seluruh lapak dan pedagang KantinQuick.</p>
            </div>
            <!-- Tombol Tambah -->
            <a href="{{ route('admin.stands.create') }}" class="inline-flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white font-bold px-5 py-3 rounded-xl shadow-lg shadow-orange-100 transition text-sm gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Stan Baru
            </a>
        </div>

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Tabel Data -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100">
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center w-16">Foto</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Stan</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pemilik (User)</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">No. Lapak</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($stands as $stand)
                            <tr class="hover:bg-gray-50/40 transition">
                                <td class="p-4 text-center">
                                    @if($stand->image)
                                        <img src="{{ asset('storage/' . $stand->image) }}" class="w-12 h-12 rounded-xl object-cover inline-block border border-gray-100 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center inline-flex border border-orange-100">
                                            <i class="fa-solid fa-utensils text-sm"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-900 text-sm hover:text-orange-500 transition cursor-pointer">{{ $stand->stand_name }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5 line-clamp-1 max-w-xs">{{ $stand->description ?? 'Tidak ada deskripsi.' }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-[10px] font-bold">
                                            {{ strtoupper(substr($stand->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-700">{{ $stand->user->name ?? 'User Terhapus' }}</div>
                                            <div class="text-[10px] text-gray-400">ID Akun: {{ $stand->user_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200/60">
                                        {{ $stand->stand_number }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($stand->status)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Buka
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Tutup
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400">
                                    <div class="text-3xl mb-2">🏪</div>
                                    <p class="text-sm font-medium">Belum ada stan kuliner yang didaftarkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
    @csrf
    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-md shadow-rose-100">
        <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout Admin
    </button>
</form>

</body>
</html>