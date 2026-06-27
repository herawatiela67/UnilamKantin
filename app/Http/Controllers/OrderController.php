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
     * SISI CUSTOMER: Mahasiswa melakukan Checkout/Pemesanan Makanan (SPLIT MULTI-STAND ORDER)
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
            
            // 2. AMBIL DATA KERANJANG MAHASISWA & SEKALIGUS ME-LOAD DATA MENU
            $cartItems = \App\Models\Cart::with('menu')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Keranjang belanja kamu kosong, El!'
                ], 400);
            }

            // 🟢 INTI PERBAIKAN: Kelompokkan item keranjang berdasarkan stand_id si menu
            $groupedCartItems = $cartItems->groupBy(function($item) {
                return $item->menu->stand_id;
            });

            $totalGrossAmount = 0; // Untuk akumulasi nominal total belanja ke Midtrans
            $createdOrders = [];   // Menyimpan list orderan yang berhasil dipecah

            // Pembuatan string ID acak untuk menyatukan invoice split di Midtrans
            $groupInvoiceGroupId = 'GROUP-' . $user->id . '-' . time(); 

            // 3. LOOPING MEMBUAT PESANAN PER STAND
            foreach ($groupedCartItems as $standId => $items) {
                
                // Hitung total harga khusus untuk stan ini
                $standTotalPrice = 0;
                foreach ($items as $item) {
                    $standTotalPrice += ($item->menu->price * $item->quantity);
                }

                $totalGrossAmount += $standTotalPrice;

                // Buat data induk pesanan UNTUK STAND INI SAJA
                $order = Order::create([
                    'user_id' => $user->id,
                    'stand_id' => $standId, // 🟢 Sekarang terkunci akurat per stand!
                    'total_price' => $standTotalPrice,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method, 
                    'payment_channel' => $request->payment_method === 'cash' ? null : $request->payment_channel,
                    'payment_status' => 'unpaid',
                ]);

                // Simpan rincian makanan khusus milik stan ini
                foreach ($items as $item) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'menu_id'  => $item->menu_id,
                        'quantity' => $item->quantity,
                        'price'    => $item->menu->price,
                    ]);
                }

                $createdOrders[] = $order;
            }

            $redirectUrl = null; // Default null untuk jalur Cash

            // 4. INTEGRASI COUPLING MIDTRANS (Akumulasi Total dari Semua Stan)
            if ($request->payment_method === 'cashless') {
                \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
                \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

                // Siapkan payload transaksi gabungan
                $params = [
                    'transaction_details' => [
                        'order_id' => $groupInvoiceGroupId, // ID Group transaksi gabungan
                        'gross_amount' => (int) $totalGrossAmount,
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email ?? $user->name . '@mail.com',
                    ],
                ];

                // Tembak API Midtrans untuk mendapatkan URL Pembayaran SandBox
                $redirectUrl = \Midtrans\Snap::getSnapUrl($params);
            }

            // 5. BERSIHKAN KERANJANG BELANJA MAHASISWA
            \App\Models\Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // 6. Respons JSON balik ke JavaScript front-end
            return response()->json([
                'status' => 'success',
                'message' => $request->payment_method === 'cashless' ? 
                'Tautan pembayaran cashless berhasil dibuat. Mengalihkan...' : 
                'Pesanan berhasil dibuat! Menu otomatis dikirim ke masing-masing stan penjual.',
                'redirect_url' => $redirectUrl,
                'data' => $createdOrders
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

            $stand = Stand::where('user_id', $userId)->first();

            if (!$stand) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stand tidak ditemukan untuk akun pedagang ini.'
                ], 404);
            }

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
     * SISI CUSTOMER & MERCHANT: Mengubah status pesanan
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,cooking,ready,completed,cancelled',
        ]);
        
        try {
            $user = auth()->user();
            $userId = $user->id;
            $stand = Stand::where('user_id', $userId)->first();
            
            if ($user->role === 'customer') {
                $order = Order::where('id', $id)->where('user_id', $userId)->first();
                
                if (!$order) {
                    return response()->json([ 'status' => 'error', 'message' => 'Pesanan tidak ditemukan.' ], 404);
                }

                if ($request->status === 'cancelled') {
                    if ($order->status !== 'pending') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Gagal batalin, pesananmu udah terlanjur dimasak sama pedagang, La!'
                        ], 422);
                    }
                } else {
                    return response()->json([ 'status' => 'error', 'message' => 'Akses ditolak. Kamu cuma bisa membatalkan pesanan sendiri.' ], 403);
                }
                
            } else {
                if (!$stand) {
                    return response()->json([ 'status' => 'error', 'message' => 'Stand tidak ditemukan untuk akun ini.' ], 404);
                }

                $order = Order::where('id', $id)
                    ->where(function($query) use ($stand) {
                        $query->where('stand_id', $stand->id)
                              ->orWhereHas('orderdetails.menu', function($q) use ($stand){
                                  $q->where('stand_id', $stand->id);
                              });
                    })->first();

                if (!$order) {
                    return response()->json([ 'status' => 'error', 'message' => 'Pesanan tidak ditemukan atau bukan milik stand Anda.' ], 404);
                }

                if ($order->status === 'cancelled') {
                    return response()->json([ 'status' => 'error', 'message' => 'Gagal! Pesanan ini sudah dibatalkan oleh mahasiswa sebelumnya.' ], 422);
                }
            }

            $order->update([ 'status' => $request->status ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Status pesanan berhasil diperbarui menjadi: ' . $request->status,
                'data' => [
                    'order_id' => $order->id,
                    'status_terbaru' => $order->status
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([ 'status' => 'error', 'message' => 'Gagal memperbarui status: ' . $e->getMessage() ], 500);
        }
    }

    public function getMerchantOrders(Request $request)
    {
        try {
            $userId = auth()->id(); 

            $orders = Order::whereHas('orderdetails.menu', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhere('merchant_id', $userId);
                })
                ->with([ 'user:id,name', 'orderdetails.menu' ])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([ 'status' => 'success', 'data' => $orders ], 200);

        } catch (\Exception $e) {
            return response()->json([ 'status' => 'error', 'message' => 'Gagal memuat pesanan merchant: ' . $e->getMessage() ], 500);
        }
    }

    public function show(string $id) {}
   
    public function destroy(string $id) {}
}