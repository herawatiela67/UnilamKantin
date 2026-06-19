<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * SISI CUSTOMER: Mahasiswa melakukan Checkout/Pemesanan Makanan
     */
    public function store(Request $request)
    {
        // 1. Validasi input array keranjang belanja
        $request->validate([
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,cashless',
            'payment_channel' => 'required_if:payment_method,cashless|nullable|string',
        ]);

        // Ambil data user mahasiswa yang sedang login beserta saldo terbarunya
        $user = $request->user();

        DB::beginTransaction();
        try {
            $totalPrice = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                $subTotal = $menu->price * $item['quantity'];
                $totalPrice += $subTotal;

                $orderItems[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'menu_model' => $menu 
                ];
            }
            //logika khusus cashless
            $paymentStatus ='unpaid';
            if ($request->payment_method === 'cashless') {
                //cek apakah saldonya cukup
                if ($user->saldo < $totalPrice) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Saldo Anda tidak mencukupi untuk melakukan pembayaran'
                    ], 200);
                }

                //jika saldo cukup, potong saldo mahasiswa
                $user->decrement('saldo', $totalPrice);
                $paymentStatus = 'paid'; // otomatis jadi lunas
            }

            // 2. Simpan data ke tabel 'orders'
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_channel' => $request->payment_channel,
                'payment_status' => $paymentStatus,
            ]);

            // 3. Simpan ke order_details & potong stok otomatis
            // 3. Simpan ke order_details
            foreach ($orderItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $request->payment_method === 'cashless'? 
                'Pesanan berhasil dibuat. Saldo Anda berhasil dipotong' : 'Pesanan berhasil dibuat dengan metode Cash. Silakan siapkan uang tunai saat mengambil makanan',
                'data' => $order->load('orderdetails.menu')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SISI CUSTOMER: Menampilkan riwayat pesanan milik mahasiswa yang sedang login
     */
    public function historyCustomer(Request $request)
    {
        try {
            $userId = $request->user()->id;

            $orders = Order::with(['user', 'orderdetails.menu'])
                ->where('user_id', $userId)
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memuat riwayat pesanan.',
                'data' => $orders
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat riwayat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SISI MERCHANT: Menampilkan pesanan masuk khusus untuk stan pedagang yang login
     */
    public function ordersForMerchant(Request $request)
    {
        try {
            $userId = $request->user()->id;

            // Cari stand yang terikat dengan user merchant ini
            $stand = Stand::where('user_id', $userId)->first();

            if (!$stand) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stand tidak ditemukan untuk akun pedagang ini.'
                ], 404);
            }

            // AMBIL NOTA YANG HANYA BERISI MENU MILIK STAND INI
            $orders = Order::whereHas('orderdetails.menu', function ($query) use ($stand) {
                    $query->where('stand_id', $stand->id);
                })
                ->with(['user', 'orderdetails.menu' => function ($query) use ($stand) {
                    $query->where('stand_id', $stand->id);
                }])
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memuat pesanan masuk untuk stand Anda.',
                'stand_name' => $stand->stand_name,
                'data' => $orders
            ], 200);

            // 🛡️ PENGAMAN: Jika status di database sudah cancelled, tolak!
            if ($order->status === 'cancelled') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal! Pesanan ini sudah dibatalkan oleh mahasiswa.'
                ], 422); // Status code 422 artinya data tidak bisa diproses
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat pesanan pedagang: ' . $e->getMessage()
            ], 500);
        }
        
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id){
    // Validasi input yang dikirim (ditambah 'confirmed' & 'cancelled')
    $request->validate([
        'status' => 'required|in:pending,cooking,ready,completed,cancelled',
    ]);
    
    try {
        $user = $request->user();
        $userId = $user->id;

        // cari stand milik pedagang yang sedang login
        $stand = Stand::where('user_id', $userId)->first();
        
        // --- 🛡️ SATPAM PENGAMAN KHUSUS MAHASISWA (CUSTOMER) ---
        if ($user->role === 'customer') {
            // Cari data orderan si mahasiswa
            $order = Order::where('id', $id)->where('user_id', $userId)->first();
            
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pesanan tidak ditemukan.',
                ], 404);
            }

            // Jika mahasiswa mau cancel, cek statusnya dulu
            if ($request->status === 'cancelled') {
                if ($order->status !== 'pending') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gagal batalin, pesananmu udah terlanjur dimasak sama pedagang, La!'
                    ], 422);
                }
            } else {
                // Mahasiswa tidak boleh mengubah status ke selain cancelled (misal tiba-tiba set ke completed)
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak. Kamu cuma bisa membatalkan pesanan sendiri.'
                ], 403);
            }
            
        } else {
            // --- 🏪 LOGIKA JALUR PEDAGANG (MERCHANT) ---
            if (!$stand) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stand tidak ditemukan untuk akun ini'
                ], 404);
            }

            // cari data order berdasarkan id dan pastikan milik stand pedagang ini
            $order = Order::where('id', $id)->whereHas('orderdetails.menu', function($query) use ($stand){
                $query->where('stand_id', $stand->id);
            })->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pesanan tidak ditemukan atau bukan milik stand anda',
                ], 404);
            }
        }

        // --- 💾 PROSES UPDATE KE DATABASE (Berlaku untuk keduanya) ---
        $order->update([
            'status' => $request->status 
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status pesanan berhasil diperbarui menjadi: ' . $request->status,
            'data' => [
                'order_id' => $order->id,
                'status_terbaru' => $order->status
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal memperbarui status: ' . $e->getMessage()
        ], 500);
    }
    }

    public function getMerchantOrders(Request $request)
    {
        try {
            // 1. Ambil ID pedagang yang sedang login
            $userId = $request->user()->id; 

            // 2. Ambil pesanan yang mengandung detail menu jualan milik merchant yang sedang login
            // Kita gunakan whereHas untuk memastikan data yang ditarik hanya milik merchant ini
            $orders = Order::whereHas('orderDetails.menu', function ($query) use ($userId) {
                    // SINKRONISASI: Sesuaikan kolom penanda merchant kamu di sini (misal user_id atau merchant_id)
                    $query->where('user_id', $userId)
                        ->orWhere('merchant_id', $userId);
                })
                ->with([
                    'user:id,name', // Mengambil nama mahasiswa yang memesan
                    'orderDetails.menu' // Mengambil data menu objek secara langsung tanpa sub-query yang rentan salah kolom
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $orders
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat pesanan merchant: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkNotifications()
{
    $userId = auth()->id();

    // Hitung berapa pesanan milik mahasiswa ini yang statusnya diubah stan dan BELUM DIBACA (is_read = 0)
    // Dan statusnya bukan lagi 'pending' / 'masuk' (artinya sudah mulai diproses stan)
    $unreadCount = \App\Models\Order::where('user_id', $userId)
        ->where('is_read', 0)
        ->whereIn('status', ['dimasak', 'siap diambil', 'selesai'])
        ->count();

    return response()->json([
        'unreadCount' => $unreadCount
    ]);
}
    public function destroy(string $id)
    {
        //
    }
}