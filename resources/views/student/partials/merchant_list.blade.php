 <div class="space-y-4">
        @foreach($stands as $stand)
            @php
                $imgUrl = $stand->image ? asset('storage/' . $stand->image) : 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500';
            @endphp

            <a href="{{ route('student.stand.detail', $stand->id) }}" class="block bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md hover:border-orange-100 transition duration-200">
                <div class="relative h-36 bg-gray-100">
                    <img src="{{ $imgUrl }}" alt="{{ $stand->stand_name }}" class="w-full h-full object-cover">
                    @if($stand->status == 1)
                        <div class="absolute top-2.5 left-2.5 bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded shadow-sm flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span> Buka
                        </div>
                    @else
                        <div class="absolute top-2.5 left-2.5 bg-red-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded shadow-sm">
                            Tutup
                        </div>
                    @endif
                </div>

                <div class="p-3">
                    <h3 class="font-bold text-sm text-gray-900 truncate">{{ $stand->stand_name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">
                        {{ $stand->description ??  ($stand->stand_number ?? '-') }}
                    </p>
                    
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-50">
                       @if($stand->orders_count > 0)
                            <span class="text-[10px] bg-amber-50 text-amber-600 px-2 py-0.5 rounded font-bold border border-amber-100">
                                <i class="fa-solid fa-fire text-[9px] mr-1 animate-bounce"></i> {{ $stand->orders_count }} Antrean
                            </span>
                        @else
                            <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-bold border border-emerald-100">
                                <i class="fa-solid fa-bolt text-[9px] mr-1"></i> Bebas Antrean
                            </span>
                        @endif
                        
                        <span class="text-xs font-bold text-orange-500 flex items-center gap-1">
                            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i> 
                            @php
                                $ratingOtomatis = 4.4 + min(($stand->total_terjual / 5) * 0.1, 0.6);
                            @endphp
                            {{ number_format($ratingOtomatis, 1) }}
                        </span>                  
                    </div>
                </div>
            </a>
        @endforeach
    </div>