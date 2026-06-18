<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantOrderController extends Controller
{
    public function index()
{
    $merchant = Auth::user();
    // Ambil data stan pedagang
    $stand = $merchant->stand ?? \App\Models\Stand::where('id', $merchant->stand_id)->first();

    if (!$stand) {
        return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan stan kuliner manapun.');
    }

    // 1. Ambil data menu milik stan ini (untuk kode lama kamu)
    $menus = \App\Models\Menu::where('stand_id', $stand->id)->get();

    // 2. Ambil data order khusus milik stan ini (untuk fitur baru)
    $orders = \App\Models\Order::with(['user', 'orderDetails.menu'])
        ->where('stand_id', $stand->id)
        ->orderBy('created_at', 'desc')
        ->get();

    // Pecah data order berdasarkan status untuk Tab Internal Pesanan
    $orderMasuk    = $orders->where('status', 'diterima');
    $sedangDimasak = $orders->where('status', 'dimasak');
    $orderSelesai  = $orders->whereIn('status', ['siap diambil', 'selesai']);

    // Mengembalikan ke view utama merchant
    return view('merchant.home_merchant', compact('stand', 'menus', 'orderMasuk', 'sedangDimasak', 'orderSelesai'));
}

   public function updateStatus(Request $request, $id)
    {
        // 1. Cari data order berdasarkan ID yang dikirim
        $order = Order::findOrFail($id);

        // 2. Ambil status baru dari form input (misal: 'dimasak', 'siap diambil')
        // Kita beri fallback default 'dimasak' kalau inputan dari form kosong
        $newStatus = $request->input('status', 'dimasak');

        // 3. Update status data tersebut
        $order->status = $newStatus;
        
        // 4. WAJIB: Simpan perubahan ke database MariaDB kamu!
        $order->save();

        // 5. Kembalikan halaman ke dashboard merchant dengan pesan sukses
        return redirect()->route('merchant.home')->with('success', 'Status pesanan berhasil diperbarui!');
    }

}