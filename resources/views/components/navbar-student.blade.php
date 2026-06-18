@props(['active' => 'home'])

<div class="fixed bottom-0 max-w-md w-full bg-white border-t border-gray-100 shadow-lg z-50 left-0 right-0 mx-auto">
    <nav class="grid grid-cols-4 w-full h-16 bg-white">
        
        <a href="{{ route('student.home') }}" class="flex flex-col items-center justify-center text-gray-400 hover:text-orange-500 transition">
            <i class="fa-solid fa-house text-xl"></i>
            <span class="text-[10px] font-medium mt-1">Home</span>
        </a>

        <a href="{{ route('orders.index') }}" class="flex flex-col items-center justify-center text-gray-400 hover:text-orange-500 transition">
            <i class="fa-regular fa-clipboard text-xl"></i>
            <span class="text-[10px] font-medium mt-1">Orders</span>
        </a>

        <a href="{{ route('student.cart') }}" class="relative flex flex-col items-center justify-center text-gray-700 hover:text-orange-500 transition">
            <div class="relative p-1 inline-flex items-center justify-center">
                <i class="fa-solid fa-cart-shopping text-xl"></i>
                
                @php
                    $cartCount = \DB::table('carts')->where('user_id', Auth::id())->sum('quantity'); 
                @endphp

                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1.5 bg-orange-500 text-white font-extrabold text-[9px] w-4 h-4 rounded-full flex items-center justify-center shadow-sm border border-white z-20">
                        {{ $cartCount }}
                    </span>
                @else
                    <span class="absolute -top-1 -right-1.5 bg-gray-400 text-white font-extrabold text-[9px] w-4 h-4 rounded-full flex items-center justify-center shadow-sm border border-white z-20">
                        0
                    </span>
                @endif
            </div>
            <span class="text-[10px] font-medium mt-1">Cart</span>
        </a>

        <a href="{{ route('student.profile') }}" class="flex flex-col items-center justify-center {{ isset($active) && $active == 'profile' ? 'text-orange-500 font-black' : 'text-gray-400 hover:text-orange-500' }} transition">
            <i class="fa-solid fa-user text-xl"></i>
            <span class="text-[10px] font-medium mt-1">Profile</span>
         </a>

    </nav>
</div>