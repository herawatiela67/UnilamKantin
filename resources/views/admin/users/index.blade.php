<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin Admin - Manajemen User</title>
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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ Route::is('admin.dashboard') ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie text-sm w-5"></i> Dashboard
                </a>
                <a href="{{ route('admin.stands.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ Route::is('admin.stands.*') ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-store text-sm w-5"></i> Manajemen Stan
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition bg-orange-500 text-white shadow-md shadow-orange-500/20">
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
        
        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl font-bold flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-base text-emerald-600"></i>
            {{ session('success') }}
        </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight">Manajemen Pengguna Aplikasi</h1>
                <p class="text-xs text-slate-500 mt-1 font-medium">Kelola hak akses, status perizinan, dan akun pengguna baik Mahasiswa maupun Merchant (Pedagang).</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase font-bold text-[10px] tracking-wider">
                            <th class="py-4 px-6">Informasi Profil</th>
                            <th class="py-4 px-6">Alamat Email</th>
                            <th class="py-4 px-6 w-36">Hak Akses (Role)</th>
                            <th class="py-4 px-6 w-40">Tanggal Terdaftar</th>
                            <th class="py-4 px-6 w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-xs font-medium text-slate-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shadow-sm border {{ $user->role === 'merchant' ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-black text-sm text-slate-900 block">{{ $user->name }}</span>
                                        <span class="text-slate-400 text-[10px] block">User ID: #KQ-{{ $user->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-6 font-semibold text-slate-600">
                                {{ $user->email }}
                            </td>

                            <td class="py-4 px-6">
                                @if($user->role === 'merchant')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 font-bold text-[10px] border border-purple-200 uppercase">
                                        <i class="fa-solid fa-users-gear text-[9px]"></i> Merchant
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-[10px] border border-blue-200 uppercase">
                                        <i class="fa-solid fa-graduation-cap text-[9px]"></i> {{ $user->role }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-slate-400 text-[11px]">
                                {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                            </td>

                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')" title="Ubah User" class="w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:bg-orange-500 hover:text-white hover:border-orange-500 flex items-center justify-center transition shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-user-pen text-xs"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna {{ $user->name }}? Semua data stan yang terikat mungkin akan ikut terpengaruh.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus User" class="w-8 h-8 rounded-lg border border-slate-200 text-rose-500 hover:bg-rose-500 hover:text-white hover:border-rose-500 flex items-center justify-center transition shadow-sm cursor-pointer">
                                            <i class="fa-solid fa-user-xmark text-xs"></i>
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

    <div id="editModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-orange-500"></i> Edit Akses Pengguna
                </h3>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-sm cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="editForm" method="POST" action="">
                @csrf
                @method('PATCH')

                <div class="space-y-4 text-xs font-bold text-slate-600">
                    <div>
                        <label class="block mb-1.5">Nama Pengguna</label>
                        <input type="text" id="modalName" name="name" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 font-semibold focus:outline-none focus:border-orange-500 text-slate-800 bg-slate-50">
                    </div>

                    <div>
                        <label class="block mb-1.5">Hak Akses / Role Aplikasi</label>
                        <select id="modalRole" name="role" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 font-bold focus:outline-none focus:border-orange-500 text-slate-800 bg-slate-50 cursor-pointer">
                            <option value="mahasiswa">CUSTOMER / MAHASISWA</option>
                            <option value="merchant">MERCHANT / PEDAGANG</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-600 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-black text-xs shadow-md shadow-orange-500/20 cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditModal(id, name, role) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        // Set input nilai default berdasarkan baris yang diklik
        document.getElementById('modalName').value = name;
        document.getElementById('modalRole').value = role;
        
        // Set action URL secara dinamis ke rute patch Laravel
        form.action = `/admin/users/${id}`;
        
        // Munculkan modal
        modal.classList.remove('hidden');
    } // 🟢 Sudah aman ada kurawal tutupnya

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        // Sembunyikan kembali modal dengan menambahkan class 'hidden'
        modal.classList.add('hidden');
    } // 🟢 Sudah aman dan kodenya bersih
</script>
</body>
</html>