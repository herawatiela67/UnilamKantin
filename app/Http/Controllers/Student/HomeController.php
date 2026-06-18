<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Stand; 
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class HomeController extends Controller
{
   public function index()
{
    // 1. KODE ASLI KAMU (Jangan diganggu gugat)
    $menus = \App\Models\Menu::where('status', 'available')->get();
    
    // Ambil data stands untuk dashboard mahasiswa (ini yang bikin eror karena sebelumnya hilang)
    $stands = \App\Models\Stand::all(); 

    // 2. KONEKSI TAMBAHAN BARU (Hanya menyisipkan tracking tanpa mengubah yang di atas)
    $activeOrders = \App\Models\Order::with(['stand', 'orderDetails.menu'])
        ->where('user_id', Auth::id())
        ->whereIn('status', ['diterima', 'dimasak', 'siap diambil'])
        ->orderBy('created_at', 'desc')
        ->get();

    // Mengelompokkan order berdasarkan waktu checkout yang sama
    $groupedOrders = $activeOrders->groupBy(function ($order) {
        return $order->created_at->format('Y-m-d H:i:s'); 
    });

    // 3. LEMPAR SEMUA VARIABEL (Variabel lama milikmu + variabel baru)
    return view('student.home', compact('menus', 'stands', 'groupedOrders'));
}

   public function showStand($id)
    {
        // 1. Cari data stand berdasarkan ID dari rute
        $stand = Stand::findOrFail($id);

        // 2. Ambil semua menu makanan milik stand tersebut
        $menus = Menu::where('stand_id', $id)->get();

        // 3. Dummy tracker agar tidak error saat halaman dimuat
        $activeOrdersTracking = collect();

        // 4. Lempar data ke view detail mahasiswa yang sudah kita buat kemarin
        return view('student.stand_detail', compact('stand', 'menus', 'activeOrdersTracking'));
    
    }
 // Pastikan Model Menu di-import di bagian atas file

 // 🟢 Pastikan urutannya: Request dulu baru $menuId
public function addToCart(Request $request, $menuId)
{
    $menu = \App\Models\Menu::findOrFail($menuId);
    $userId = auth()->id();

    $existingCart = \App\Models\Cart::where('user_id', $userId)
                                    ->where('menu_id', $menu->id)
                                    ->first();

    if ($existingCart) {
        $existingCart->update([
            'quantity' => $existingCart->quantity + $request->input('quantity', 1)
        ]);
    } else {
        \App\Models\Cart::create([
            'user_id' => $userId,
            'menu_id' => $menu->id,
            'quantity' => $request->input('quantity', 1)
        ]);
    }

    return redirect()->back()->with('success', 'Berhasil menambahkan ke keranjang!');
}
// 2. Halaman Tampil Keranjang
    public function cart()
    {
        // 1. 🟢 KUNCI UTAMA: Ambil data dari tabel carts database (Bukan Session!)
        // Kita sertakan 'with('menu')' agar data nama makanan & harganya ikut terbawa
        $cartItems = \App\Models\Cart::with('menu')->where('user_id', auth()->id())->get();

        // 2. Hitung total harga belanjaan secara otomatis dari database
        $totalPrice = $cartItems->sum(function($item) {
            // Mencegah crash jika data menu kebetulan tidak ditemukan
            return optional($item->menu)->price * $item->quantity;
        });

        // 3. Oper variabel $cartItems dan $totalPrice ke file Blade kamu
        return view('student.cart', compact('cartItems', 'totalPrice'));
    }

    /**
     * Menghapus Item dari Keranjang Belanja
     */
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $namaMenu = $cart[$id]['name'];
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', $namaMenu . ' berhasil dihapus dari keranjang!');
        }

        return redirect()->back();
    }


// 4. Proses Checkout Kirim ke Database
public function checkout(Request $request)
{
    
    // 1. Ambil data keranjang belanja mahasiswa yang sedang login
    // (Sesuaikan dengan cara kamu menyimpan cart, misal dari database tabel 'carts' atau session)
    $cartItems = \App\Models\Cart::where('user_id', auth()->id())->get(); 

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Keranjang belanja kamu masih kosong!');
    }

    // 2. Ambil stan_id dari salah satu item di keranjang (karena checkout per stan)
    $firstItem = $cartItems->first();
    $standId = $firstItem->menu->stand_id; 

    // 3. Hitung total harga seluruh belanjaan di keranjang
    $totalPrice = $cartItems->sum(function($item) {
        return $item->menu->price * $item->quantity;
    });

    // 4. SIMPAN KE TABEL ORDERS (Induk Pesanan)
    $order = \App\Models\Order::create([
        'user_id' => auth()->id(),
        'stand_id' => $standId,
        'total_price' => $totalPrice,
        'status' => 'pending', // Status awal langsung set pending
        'payment_method' => $request->input('payment_method', 'TUNAI'),
    ]);

    // 5. 🟢 KUNCI UTAMA: LOOPING UNTUK MENGISI TABEL ORDER_DETAILS
    foreach ($cartItems as $item) {
        \App\Models\OrderDetail::create([
            'order_id' => $order->id, // Hubungkan dengan ID Order yang baru dibuat di atas
            'menu_id' => $item->menu_id,
            'quantity' => $item->quantity,
            'price' => $item->menu->price, // Kunci harga saat dibeli (jika nanti harga menu berubah)
        ]);
    }

    // 6. Bersihkan keranjang belanja mahasiswa setelah sukses checkout
    \App\Models\Cart::where('user_id', auth()->id())->delete();

    // 7. Oper mahasiswa ke halaman tracking pesanan yang barusan dibuat
    return redirect()->route('student.order.track', $order->id)->with('success', 'Pesanan berhasil dikirim ke kantin!');
}
  public function trackOrder($id)
{
    // 1. Ambil data order utama yang diklik oleh mahasiswa
    $currentOrder = Order::where('user_id', Auth::id())->findOrFail($id);

    // Bikin alias $order tunggal supaya Blade baris 18 tidak bingung
    $order = $currentOrder; 

    // 2. Ambil semua order milik mahasiswa yang checkout-nya barengan (selisih 5 detik)
    $orders = Order::with(['stand', 'orderDetails.menu'])
        ->where('user_id', Auth::id())
        ->where('created_at', '>=', $currentOrder->created_at->subSeconds(5))
        ->where('created_at', '<=', $currentOrder->created_at->addSeconds(5))
        ->get();

    // 🟢 KUNCI UTAMA: Pastikan $order ikut dilempar di dalam compact!
    return view('student.track', compact('orders', 'currentOrder', 'order'));
}

    public function ordersHistory()
    {
        // Ambil semua pesanan milik mahasiswa yang sedang login, urutkan dari yang paling baru
        $orders = Order::with(['stand'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Render ke halaman view history (bisa kamu buat filenya nanti)
        return view('student.orders_index', compact('orders'));
    }

    public function profileStudent() {
    // Mengambil data user yang sedang login saat ini
    $user = Auth::user();
    
    // Mengembalikan ke file view blade profile yang akan kita buat
    return view('student.profile', compact('user'));
    }
}