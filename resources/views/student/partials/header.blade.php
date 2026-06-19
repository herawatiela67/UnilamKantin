<header class="bg-white px-5 pt-5 pb-3 flex justify-between items-center sticky top-0 z-50 shadow-sm border-b border-gray-50">
    <div>
        <div class="flex items-center gap-1.5 text-orange-600">
            <i class="fa-solid fa-location-dot text-sm"></i>
            <span class="font-extrabold text-sm text-gray-900">UnilamKantin</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-500"></i>
        </div>
        @php
        $jam = date('H');
        if ($jam >= 5 && $jam < 11) {
            $sapaan = 'Mau sarapan apa pagi ini';
        } elseif ($jam >= 11 && $jam < 15) {
            $sapaan = 'Mau makan siang apa hari ini';
        } elseif ($jam >= 15 && $jam < 18) {
            $sapaan = 'Mau nyemil apa sore ini';
        } else {
            $sapaan = 'Mau makan malam apa malam ini';
        }
        @endphp 
        <span class="text-xs font-medium text-gray-400 block mt-0.5">
            {{ $sapaan }}, {{ Auth::user()->name }}? 👋
        </span>
    </div>

    <div class="flex items-center gap-3 relative">
        
        <a href="{{ route('student.notifications') }}" class="text-gray-600 hover:text-orange-500 p-1.5 relative transition active:scale-95 block">
        <i class="fa-regular fa-bell text-xl"></i>
        
        <span id="notif-badge" class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/4 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center animate-pulse shadow-sm hidden">
            0
        </span>
         </a>

        <form action="{{ route('logout') }}" method="POST" class="flex items-center">
            @csrf
            <button type="submit" class="text-gray-400 hover:text-red-500 p-1.5 transition focus:outline-none flex items-center justify-center active:scale-95" onclick="return confirm('Keluar dari KantinQuick?')">
                <i class="fa-solid fa-right-from-bracket text-lg"></i>
            </button>
        </form>
        
    </div>
</header>