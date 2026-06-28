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
                    <h2 class="font-black text-sm tracking-wider uppercase text-white">UnilamKantin</h2>
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
                                    <button onclick="openEditStandModal('{{ $stand->id }}', '{{ $stand->stand_name }}', '{{ $stand->stand_number }}', '{{ $stand->user_id }}', '{{ $stand->category }}', '{{ $stand->status }}')" title="Edit Informasi Stan" class="w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:bg-orange-500 hover:text-white hover:border-orange-500 flex items-center justify-center transition shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>

                                    <form action="{{ route('admin.stands.destroy', $stand->id) }}" method="POST" onsubmit="return confirm('Hapus stan {{ $stand->stand_name }} dari sistem KantinQuick? Seluruh data menu di stan ini mungkin akan ikut terhapus.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Stan" class="w-8 h-8 rounded-lg border border-slate-200 text-rose-500 hover:bg-rose-500 hover:text-white hover:border-rose-500 flex items-center justify-center transition shadow-sm cursor-pointer">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <div id="editStandModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-shop text-orange-500"></i> Edit Data Stan Kuliner
            </h3>
            <button onclick="closeEditStandModal()" class="text-slate-400 hover:text-slate-600 text-sm cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="editStandForm" method="POST" action="">
            @csrf
            @method('PATCH')

            <div class="space-y-4 text-xs font-bold text-slate-600">
                <div>
                    <label class="block mb-1.5">Nama Stan / Lapak</label>
                    <input type="text" id="modalStandName" name="stand_name" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-slate-800 bg-slate-50 focus:outline-none focus:border-orange-500" required>
                </div>

                <div>
                    <label class="block mb-1.5">Nomor Stan</label>
                    <input type="text" id="modalStandNumber" name="stand_number" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-slate-800 bg-slate-50 focus:outline-none focus:border-orange-500" required>
                </div>

                <div>
                    <label class="block mb-1.5">Ikat ke Akun Pedagang</label>
                    <select id="modalUserId" name="user_id" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-slate-800 bg-slate-50 focus:outline-none focus:border-orange-500 cursor-pointer" required>
                        @foreach($merchants as $merchant)
                            <option value="{{ $merchant->id }}">{{ $merchant->name }} ({{ $merchant->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1.5">Kategori Utama</label>
                    <select id="modalCategory" name="category" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-slate-800 bg-slate-50 focus:outline-none focus:border-orange-500 font-bold cursor-pointer" required>
                        <option value="Makanan Berat">MAKANAN</option>
                        <option value="Cemilan">CEMILAN</option>
                        <option value="Minuman">MINUMAN</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1.5">Status Operasional</label>
                    <select id="modalStatus" name="status" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-slate-800 bg-slate-50 focus:outline-none focus:border-orange-500 cursor-pointer" required>
                        <option value="1">BUKA / AKTIF</option>
                        <option value="0">TUTUP / ISTIRAHAT</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditStandModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-600 cursor-pointer">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-black text-xs shadow-md shadow-orange-500/20 cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditStandModal(id, name, number, userId, category, status) {
        const modal = document.getElementById('editStandModal');
        const form = document.getElementById('editStandForm');
        
        // Isi value form modal secara otomatis sesuai baris yang diklik
        document.getElementById('modalStandName').value = name;
        document.getElementById('modalStandNumber').value = number;
        document.getElementById('modalUserId').value = userId;
        document.getElementById('modalCategory').value = category;
        document.getElementById('modalStatus').value = status;
        
        // Set action form secara dinamis ke URL update backend
        form.action = `/admin/stands/${id}`;
        
        // Munculkan modal (hapus class hidden)
        modal.classList.remove('hidden');
    }

    function closeEditStandModal() {
        const modal = document.getElementById('editStandModal');
        modal.classList.add('hidden');
    }
</script>

</body>
</html>