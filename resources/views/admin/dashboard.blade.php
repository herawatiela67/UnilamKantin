<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnilamKantin - Dashboard</title>
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
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ Route::is('admin.dashboard') ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie text-sm w-5"></i> Dashboard
                </a>
                
                <a href="{{ route('admin.stands.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ Route::is('admin.stands.*') ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-store text-sm w-5"></i> Manajemen Stan
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:bg-slate-800 hover:text-white transition">
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
        <div class="mb-8">
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Selamat Datang, Admin!</h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">Berikut adalah rangkuman performa operasional aplikasi UnilamKantin hari ini.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider block">Total Lapak / Stan</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $totalStands }}</span>
                </div>
                <div class="w-11 h-11 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-lg"><i class="fa-solid fa-store"></i></div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider block">Akun Mahasiswa</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $totalCustomers }}</span>
                </div>
                <div class="w-11 h-11 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-lg"><i class="fa-solid fa-graduation-cap"></i></div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider block">Akun Pedagang</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $totalMerchants }}</span>
                </div>
                <div class="w-11 h-11 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center text-lg"><i class="fa-solid fa-users-gear"></i></div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider block">Total Pesanan</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $totalOrders }}</span>
                </div>
                <div class="w-11 h-11 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-lg"><i class="fa-solid fa-receipt"></i></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-black text-xs uppercase tracking-wider text-slate-500">Aktivitas Pesanan Terbaru</h3>
                <span class="text-[10px] bg-slate-200 text-slate-700 font-bold px-2 py-0.5 rounded-full">Realtime</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase font-bold text-[9px] tracking-wider">
                            <th class="py-3 px-6">ID Invoice</th>
                            <th class="py-3 px-6">Mahasiswa</th>
                            <th class="py-3 px-6">Stan Tujuan</th>
                            <th class="py-3 px-6">Total Harga</th>
                            <th class="py-3 px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-xs font-medium text-slate-600">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-slate-50/30 transition">
                            <td class="py-3.5 px-6 font-bold text-slate-900">#KQ-{{ $order->id }}</td>
                            <td class="py-3.5 px-6">{{ optional($order->user)->name ?? 'User N/A' }}</td>
                            <td class="py-3.5 px-6"><span class="bg-slate-100 px-2 py-0.5 rounded text-slate-700 font-bold text-[11px]">{{ optional($order->stand)->stand_name ?? 'Stan N/A' }}</span></td>
                            <td class="py-3.5 px-6 font-black text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ $order->status == 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        @if($recentOrders->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400 font-medium">Belum ada transaksi pesanan masuk.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>