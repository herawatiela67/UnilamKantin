<div class="bg-white p-5 rounded-2xl border border-gray-100 mb-4 shadow-sm hover:shadow-md transition duration-200">
    
    <div class="flex justify-between items-center pb-3 border-b border-gray-100 mb-3">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs shadow-sm">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-medium tracking-wide uppercase">Pelanggan</p>
                <h4 class="font-bold text-xs text-gray-800">{{ optional($order->user)->name ?? 'Mahasiswa Unilam' }}</h4>
            </div>
        </div>
        
        <span class="text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-lg font-extrabold uppercase tracking-wider">
            <i class="fa-solid fa-money-bill-wave mr-1"></i> {{ $order->payment_method ?? 'CASH' }}
        </span>
    </div>

    <div class="flex items-center gap-1.5 text-gray-400 text-[11px] font-medium mb-3">
        <i class="fa-regular fa-clock"></i>
        <span>Dipesan pada: {{ $order->created_at->format('H:i') }} WIB</span>
    </div>

    <div class="space-y-2 bg-slate-50 p-3.5 rounded-xl border border-dashed border-gray-200 mb-4">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Daftar Menu:</p>
        
        @foreach($order->orderDetails as $detail)
            <div class="flex justify-between items-center text-xs py-1 border-b border-gray-100 last:border-0">
                <div class="flex items-center gap-2 min-w-0 flex-1">
                    <span class="w-1.5 h-1.5 bg-orange-500 rounded-full flex-shrink-0"></span>
                    <span class="font-semibold text-gray-700 truncate mr-2">
                        {{ optional($detail->menu)->name ?? 'Menu Telah Dihapus' }}
                    </span>
                    <span class="text-[11px] font-extrabold text-orange-600 bg-orange-50 px-1.5 py-0.5 rounded-md flex-shrink-0">
                        x{{ $detail->quantity }}
                    </span>
                </div>
                <span class="font-bold text-gray-900 ml-4 flex-shrink-0">
                    Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}
                </span>
            </div>
        @endforeach
    </div>

    <div class="flex justify-between items-center pt-2">
        <div>
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Subtotal Terima</p>
            <p class="text-base font-black text-emerald-600 tracking-tight">
                Rp{{ number_format($order->total_price, 0, ',', '.') }}
            </p>
        </div>

        @if(isset($action) && $action !== 'selesai')
            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="{{ $action }}">
                <button type="submit" class="{{ $color }} text-white font-bold text-xs px-4 py-2 rounded-xl shadow-md transition transform active:scale-95 cursor-pointer">
                    {{ $btnText }}
                </button>
            </form>
        @else
            <div class="flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-xl shadow-sm">
                <i class="fa-solid fa-circle-check text-[10px]"></i>
                <span>Selesai</span>
            </div>
        @endif
    </div>
</div>