<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin Admin - Manajemen Stan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col pl-64">

    <aside class="w-64 bg-slate-900 text-white fixed top-0 bottom-0 left-0 p-5 flex flex-col justify-between shadow-xl z-50">
        <div class="space-y-6">
            <div class="flex items-center gap-3 px-2 py-3 border-b border-slate-800">
                <div class="w-9 h-9 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-500/30">
                    <i class="fa-solid fa-utensils text-base"></i>
                </div>
                <div>
                    <h2 class="font-black text-sm tracking-wider uppercase text-white">KantinQuick</h2>
                    <p class="text-[10px] text-slate-400 font-bold">PANEL UTAMA ADMIN</p>
                </div>
            </div>

            <nav class="space-y-1">
                <a href="{{ url('admin/dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-chart-pie text-sm w-5"></i> Dashboard
                </a>
                <a href="{{ url('admin/stands') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold bg-orange-500 text-white shadow-md shadow-orange-500/20 transition">
                    <i class="fa-solid fa-store text-sm w-5"></i> Manajemen Stan
                </a>
                <a href="{{ url('admin/users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-users text-sm w-5"></i> Manajemen User
                </a>
            </nav>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="border-t border-slate-800 pt-4">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-red-400 hover:bg-red-500/10 hover:text-red-300 transition text-left cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket text-sm w-5"></i> Keluar Panel
            </button>
        </form>
    </aside>

    <main class="p-8 flex-1">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight">Manajemen Stan Kuliner</h1>
                <p class="text-xs text-slate-500 mt-1 font-medium">Kelola data lapak, informasi stan, serta integrasi akun login milik pedagang.</p>
            </div>
            <a href="{{ route('admin.stands.create') }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs px-5 py-3 rounded-xl shadow-lg shadow-orange-500/20 transition transform active:scale-95 cursor-pointer">
                <i class="fa-solid fa-plus-circle text-sm"></i> Tambah Stan Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 rounded-xl mb-6 text-xs font-bold flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase font-bold text-[10px] tracking-wider">
                            <th class="py-4 px-6 w-24">Foto</th>
                            <th class="py-4 px-6">Detail Stan / Lapak</th>
                            <th class="py-4 px-6">Pemilik (Akun Login)</th>
                            <th class="py-4 px-6 w-32">Status Operasi</th>
                            <th class="py-4 px-6 w-44 text-center">Aksi / Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-xs font-medium text-slate-700">
                        @foreach($stands as $stand)
                        @php
                            $imgUrl = $stand->image ? asset('storage/' . $stand->image) : 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=100';
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6">
                                <img src="{{ $imgUrl }}" alt="{{ $stand->stand_name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-150 bg-slate-50 shadow-sm flex-shrink-0">
                            </td>

                            <td class="py-4 px-6">
                                <span class="font-black text-sm text-slate-900 block mb-0.5">{{ $stand->stand_name }}</span>
                                <span class="text-slate-400 text-[11px] block mb-1.5">{{ $stand->description ?? 'Tidak ada deskripsi singkat.' }}</span>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-bold text-[10px] border border-slate-200 uppercase">
                                    <i class="fa-solid fa-map-pin text-[9px]"></i> {{ $stand->stand_number ?? 'Lapak N/A' }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-orange-50 text-orange-600 font-bold text-[10px] border border-orange-100 uppercase ml-1">
                                    <i class="fa-solid fa-tags text-[9px]"></i> {{ $stand->category ?? 'makanan' }}
                                </span>
                            </td>

                            <td class="py-4 px-6">
                                @if($stand->user)
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-bold border border-blue-100 text-xs">
                                            {{ strtoupper(substr($stand->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block">{{ $stand->user->name }}</span>
                                            <span class="text-slate-400 text-[11px] block">{{ $stand->user->email }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 bg-rose-50 text-rose-500 rounded-lg flex items-center justify-center font-bold border border-rose-100 text-xs">
                                            <i class="fa-solid fa-user-slash"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-rose-600 block">Akun Belum Diikat</span>
                                            <span class="text-slate-400 text-[11px] block">ID Akun Terkait: {{ $stand->user_id ?? '-' }}</span>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <td class="py-4 px-6">
                                @if($stand->status == 1)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Buka
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold text-[10px] border border-rose-200">
                                        <span class="w-1.5 h-1.5 bg-rose-400 rounded-full"></span> Tutup
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="#" title="Edit Informasi Stan" class="w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:bg-orange-500 hover:text-white hover:border-orange-500 flex items-center justify-center transition shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    
                                    <a href="#" title="Kelola Akun Pedagang" class="w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:bg-blue-500 hover:text-white hover:border-blue-500 flex items-center justify-center transition shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-user-gear text-xs"></i>
                                    </a>

                                    <button onclick="return confirm('Hapus stan {{ $stand->stand_name }} dari sistem KantinQuick?')" title="Hapus Stan" class="w-8 h-8 rounded-lg border border-slate-200 text-rose-500 hover:bg-rose-500 hover:text-white hover:border-rose-500 flex items-center justify-center transition shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>