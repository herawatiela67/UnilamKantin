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
    $order = \App\Models\Order::findOrFail($id);
    
    // Validasi input status yang masuk
    $request->validate([
        'status' => 'required|in:dimasak,siap diambil,selesai,batal'
    ]);

    // Update status sesuai tombol yang diklik abang stan
    $order->status = $request->status;
    
    // Setiap kali ada perubahan status baru dari stan, 
    // set is_read jadi 0 lagi biar lonceng di HP mahasiswa nyala merah lagi!
    $order->is_read = 0; 
    
    $order->save();

    return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
}
}
