<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stand;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Mengambil hitungan data untuk ringkasan kartu statistik di dashboard
        $totalStands = Stand::count();
        $totalCustomers = User::where('role', 'mahasiswa')->count();
        $totalMerchants = User::where('role', 'merchant')->count();
        $totalOrders = Order::count();

        // Mengambil 5 pesanan terbaru di seluruh kantin untuk dipajang
        $recentOrders = Order::with(['user', 'stand'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStands', 
            'totalCustomers', 
            'totalMerchants', 
            'totalOrders',
            'recentOrders'
        ));
    }
}