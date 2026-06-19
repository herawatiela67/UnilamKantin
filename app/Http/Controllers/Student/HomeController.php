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
    // 1. DATA ASLI KAMU: Ambil semua menu yang tersedia
    $menus = \App\Models\Menu::where('status', 'available')->get();
    
    // 2. KOREKSI RATING & POPULARITAS: Ambil data stands dinamis (Urut paling laku)
    $stands = \App\Models\Stand::leftJoin('orders', function($join) {
    $join->on('stands.id', '=', 'orders.stand_id')
         ->where('orders.status', '=', 'selesai');
        })
        ->select(
            'stands.id',
            'stands.stand_name',
            'stands.stand_number',
            'stands.image',       // 🟢 Sekarang aman dipanggil!
            'stands.description', // 🟢 Sekarang aman dipanggil!
            'stands.status',
            DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.stand_id = stands.id AND orders.status IN ("masuk", "dimasak")) as orders_count'),
            DB::raw('COUNT(orders.id) as total_terjual')
        )
        ->groupBy('stands.id', 'stands.stand_name', 'stands.stand_number', 'stands.image', 'stands.description', 'stands.status')
        ->orderBy('total_terjual', 'desc')
        ->get();

    // 3. DATA ASLI KAMU: Ambil data tracking pesanan aktif milik mahasiswa
    $activeOrders = \App\Models\Order::with(['stand', 'orderDetails.menu'])
        ->where('user_id', Auth::id())
        ->whereIn('status', ['diterima', 'dimasak', 'siap diambil'])
        ->orderBy('created_at', 'desc')
        ->get();

    // 4. 🆕 FITUR NOTIFIKASI DINAMIS (Otomatis Hilang Jika Sudah Selesai Diambil)
    // Hanya mengambil pesanan yang berstatus aktif dan butuh dipantau mahasiswa
    $activeNotifications = \App\Models\Order::where('user_id', Auth::id())
        ->whereIn('status', ['pending', 'masuk', 'diterima', 'dimasak', 'siap diambil'])
        ->with('stand')
        ->orderBy('updated_at', 'desc')
        ->get();

    $menuTerbaru = DB::table('menus')
        ->join('stands', 'stands.id', '=', 'menus.stand_id')
        ->select('menus.name as menu_name', 'stands.stand_name', 'menus.price', 'menus.created_at')
        ->select('menus.id as menu_id', 'menus.name as menu_name', 'stands.id as stand_id', 'stands.stand_name', 'menus.price', 'menus.created_at')
        // 🟢 MANTRA BARU: Hanya ambil menu yang dibuat dalam 20 menit terakhir dari waktu sekarang
        ->where('menus.created_at', '>=', now()->subMinutes(20))
        ->orderBy('menus.created_at', 'desc')
        ->first();

    // Mengelompokkan order berdasarkan waktu checkout yang sama
    $groupedOrders = $activeOrders->groupBy(function ($order) {
        return $order->created_at->format('Y-m-d H:i:s'); 
    });

    // 5. LEMPAR SEMUA VARIABEL KE VIEW STUDENT (Gabungan Fitur Lama + Baru)
    // 🟢 Sudah diselipkan variabel 'activeNotifications' di baris compact ini ya, El!
    return view('student.home', compact('menus', 'stands', 'menuTerbaru', 'groupedOrders', 'activeNotifications'));
}

public function notificationPage()
{
    $userId = auth()->id();

    // 1. Ambil SEMUA pesanan milik user (termasuk yang dimasak, siap diambil, maupun yang sudah SELESAI/BATAL)
    $groupedOrders = \App\Models\Order::where('user_id', $userId)
        ->with('stand')
        ->orderBy('updated_at', 'desc') // Yang paling baru diubah statusnya ada di paling atas
        ->get()
        ->groupBy(function($date) {
            return $date->updated_at->format('Y-m-d'); // Tetap dikelompokkan pakai tanggal asli kamu
        });

    // 2. Trik Otomatis: Begitu halaman dibuka, tandai semua pesanan yang tadinya belum dibaca menjadi "Sudah Dibaca" (is_read = 1)
    \App\Models\Order::where('user_id', $userId)
        ->where('is_read', 0)
        ->update(['is_read' => 1]);

    // Oper data kelompok pesanan ke view
    return view('student.notifications', compact('groupedOrders'));
}

 public function checkNotifications()
{
    $userId = auth()->id();

    // Menghitung pesanan aktif milik mahasiswa yang statusnya diubah stan dan belum dibaca
    $unreadCount = \App\Models\Order::where('user_id', $userId)
        ->where('is_read', 0)
        ->whereIn('status', ['dimasak', 'siap diambil', 'selesai'])
        ->count();

    return response()->json([
        'unreadCount' => $unreadCount
    ]);
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
        'payment_method' => 'e-wallet', // 🟢 Pastikan teks ini sama persis dengan opsi ENUM di DB kamu!
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