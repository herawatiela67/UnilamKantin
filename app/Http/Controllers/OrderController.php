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
use Midtrans\Config;
use Midtrans\Snap;

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
    // 1. Validasi input metode pembayaran dari front-end
    $request->validate([
        'payment_method' => 'required|in:cash,cashless',
        'payment_channel' => 'required_if:payment_method,cashless|nullable|string',
    ]);

    DB::beginTransaction();
    try {
        $user = auth()->user(); 
        
        // 2. AMBIL DATA DARI TABEL CARTS (Sinkron dengan keranjang mahasiswa)
        $cartItems = \App\Models\Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang belanja kamu kosong, El!'
            ], 400);
        }

        $totalPrice = 0;
        $firstMenu = null; 

        // 3. Loop Hitung Total Harga & Ambil Data Menu Pertama
        foreach ($cartItems as $item) {
            $menu = Menu::find($item->menu_id);
            if (!$firstMenu) {
                $firstMenu = $menu; 
            }
            $totalPrice += ($menu->price * $item->quantity);
        }

        $redirectUrl = null; // Default null untuk jalur Cash

        // 4. Buat data induk pesanan (Dinamis sesuai pilihan)
        $order = Order::create([
            'user_id' => $user->id,
            'stand_id' => $firstMenu ? $firstMenu->stand_id : null,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_method' => $request->payment_method, 
            'payment_channel' => $request->payment_method === 'cash' ? null : $request->payment_channel,
            'payment_status' => 'unpaid',
        ]);

        // 5. Simpan rincian makanan ke tabel 'order_details'
        foreach ($cartItems as $item) {
            $menu = Menu::find($item->menu_id);
            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id'  => $menu->id,
                'quantity' => $item->quantity,
                'price'    => $menu->price, // Kunci harga saat ini
            ]);
        }

        // 🔥 6. INTEGRASI COUPLING MIDTRANS UNTUK JALUR CASHLESS 🔥
        if ($request->payment_method === 'cashless') {
            // Konfigurasi dasar Midtrans
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

            // Siapkan payload transaksi
            $params = [
                'transaction_details' => [
                    'order_id' => 'KANTIN-' . $order->id . '-' . time(), // ID unik anti-bentrok
                    'gross_amount' => (int) $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email ?? $user->name . '@mail.com',
                ],
            ];

            // Tembak API Midtrans untuk mendapatkan URL Pembayaran SandBox
            $redirectUrl = \Midtrans\Snap::getSnapUrl($params);
            
            // Simpan snap token ke orderan (opsional, jika kolomnya ada)
            // $order->update(['snap_token' => $redirectUrl]);
        }

        // 7. BERSIHKAN KERANJANG BELANJA (Karena pesanan sudah diproses)
        \App\Models\Cart::where('user_id', $user->id)->delete();

        DB::commit();

        // 8. Respons JSON balik ke JavaScript fetch() front-end
        return response()->json([
            'status' => 'success',
            'message' => $request->payment_method === 'cashless' ? 
            'Tautan pembayaran cashless berhasil dibuat. Mengalihkan...' : 
            'Pesanan berhasil dibuat dengan metode Cash. Silakan siapkan uang tunai saat mengambil makanan.',
            'redirect_url' => $redirectUrl, // Berisi link Midtrans atau null
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
            $userId = auth()->id();

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
     * SISI MERCHANT: Menampilkan pesanan masuk khusus untuk stan pedagang yang login (Berdasarkan stand_id)
     */
    public function ordersForMerchant(Request $request)
    {
        try {
            $userId = auth()->id();

            // Cari stand yang terikat dengan akun pedagang yang sedang login
            $stand = Stand::where('user_id', $userId)->first();

            if (!$stand) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stand tidak ditemukan untuk akun pedagang ini.'
                ], 404);
            }

            // Ambil data pesanan yang stand_id nya cocok dan memuat detail menunya
            $orders = Order::where('stand_id', $stand->id)
                ->orWhereHas('orderdetails.menu', function ($query) use ($stand) {
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

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat pesanan pedagang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SISI CUSTOMER & MERCHANT: Mengubah status pesanan (cooking, ready, completed, cancelled)
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,cooking,ready,completed,cancelled',
        ]);
        
        try {
            $user = auth()->user();
            $userId = $user->id;

            // Cari stand milik pedagang yang sedang login
            $stand = Stand::where('user_id', $userId)->first();
            
            // --- 🛡️ JALUR MAHASISWA (CUSTOMER) ---
            if ($user->role === 'customer') {
                $order = Order::where('id', $id)->where('user_id', $userId)->first();
                
                if (!$order) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pesanan tidak ditemukan.',
                    ], 404);
                }

                // Pengaman: Mahasiswa hanya boleh membatalkan jika status pesanan masih 'pending'
                if ($request->status === 'cancelled') {
                    if ($order->status !== 'pending') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Gagal batalin, pesananmu udah terlanjur dimasak sama pedagang, La!'
                        ], 422);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Akses ditolak. Kamu cuma bisa membatalkan pesanan sendiri.'
                    ], 403);
                }
                
            } else {
                // --- 🏪 JALUR PEDAGANG (MERCHANT) ---
                if (!$stand) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Stand tidak ditemukan untuk akun ini.'
                    ], 404);
                }

                // Ambil data order milik stand pedagang ini
                $order = Order::where('id', $id)
                    ->where(function($query) use ($stand) {
                        $query->where('stand_id', $stand->id)
                              ->orWhereHas('orderdetails.menu', function($q) use ($stand){
                                  $q->where('stand_id', $stand->id);
                              });
                    })->first();

                if (!$order) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pesanan tidak ditemukan atau bukan milik stand Anda.',
                    ], 404);
                }

                // Pengaman tambahan: Jika pesanan sudah terlanjur dibatalkan mahasiswa, pedagang tidak bisa memprosesnya lagi
                if ($order->status === 'cancelled') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gagal! Pesanan ini sudah dibatalkan oleh mahasiswa sebelumnya.'
                    ], 422);
                }
            }

            // --- 💾 PROSES UPDATE STATUS KE DATABASE ---
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

    /**
     * SISI MERCHANT: Cadangan pengambilan data pesanan merchant berdasarkan keterikatan menu jualan
     */
    public function getMerchantOrders(Request $request)
    {
        try {
            $userId = auth()->id(); 

            // Menarik data orders yang disaring agar konsisten menggunakan nama relasi camelCase/lowercase 'orderdetails'
            $orders = Order::whereHas('orderdetails.menu', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhere('merchant_id', $userId);
                })
                ->with([
                    'user:id,name', 
                    'orderdetails.menu' 
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

    public function show(string $id)
    {
        //
    }
   
    public function destroy(string $id)
    {
        //
    }
}