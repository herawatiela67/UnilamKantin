<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Stand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Menampilkan semua menu (untuk customer/mahasiswa di web)
     */
    /**
 * Menampilkan halaman utama mahasiswa berisi daftar Stand Kantin
 */
    public function index()
    {
        // Ambil semua data stand dari database MySQL
        $stands = \App\Models\Stand::all(); 
        
        // Lempar data $stands ke halaman depan mahasiswa
        return view('student.home', compact('stands'));
    }

    /**
     * Menampilkan menu khusus di dashboard merchant/pedagang (Web Blade)
     */
 public function indexMerchant()
{
    $merchant = Auth::user();
    
    // 1. Ambil data stan milik pedagang yang sedang login
    $stand = \App\Models\Stand::where('user_id', $merchant->id)->first();

    if (!$stand) {
        return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan stan kuliner manapun.');
    }

    // 2. Ambil daftar menu jualan (Kode lama kamu)
    $menus = \App\Models\Menu::where('stand_id', $stand->id)->get();

    // 3. 🟢 TAMBAHAN BARU: Ambil data pesanan mahasiswa khusus untuk stan ini
    $orders = \App\Models\Order::with(['user', 'orderDetails.menu'])
            ->where('stand_id', $stand->id)
            ->orderBy('created_at', 'asc') // 'asc' membuat yang duluan masuk ada di paling atas
            ->get();

    // 4. 🟢 PERBAIKAN FILTER: Menggunakan 'pending' (sesuai status awal di database KantinQuick)
    // Jika di DB kamu status awalnya 'pending', maka wajib pakai 'pending' agar datanya lolos filter dan muncul menunya
    $orderMasuk    = $orders->whereIn('status', ['pending', 'diterima']); 
    $sedangDimasak = $orders->where('status', 'dimasak');
    $orderSelesai  = $orders->whereIn('status', ['siap diambil', 'selesai']);

    // 5. KUNCI SUKSES: Lemparkan semua variabel baru ke dalam compact()
    return view('merchant.home', compact('stand', 'menus', 'orderMasuk', 'sedangDimasak', 'orderSelesai'));
}
    /**
     * Menampilkan formulir tambah menu baru (Web Blade)
     */
   /**
     * Menampilkan formulir tambah menu baru (Create Mode)
     */
    public function create()
    {
        return view('merchant.add_edit_menu', ['menu' => null]); // Kirim null agar terbaca mode "Tambah"
    }

    /**
     * Menambahkan menu baru ke MySQL
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:100',
            'status'      => 'required|in:available,unavailable',
            'description' => 'nullable|string', 
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $user = Auth::user();
        $stand = Stand::where('user_id', $user->id)->first();

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
        }

        Menu::create([
            'stand_id'    => $stand->id,
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description, 
            'status'      => $request->status,
            'image'       => $path ? 'storage/' . $path : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500', 
        ]);

        return redirect()->route('merchant.home')->with('success', 'Menu Baru Berhasil Ditambahkan!');
    }

    /**
     * Menampilkan formulir edit menu (Edit Mode)
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('merchant.add_edit_menu', compact('menu')); // Kirim data menu lama ke Blade
    }

    /**
     * Mengupdate data menu lama di MySQL
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:100',
            'status'      => 'required|in:available,unavailable',
            'description' => 'nullable|string', 
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $updateData = [
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description, 
            'status'      => $request->status,
        ];

        // Jalankan logika hapus & timpa foto persis bawaan API Flutter kamu dulu
        if ($request->hasFile('image')) {
            if ($menu->image && !str_contains($menu->image, 'http')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $menu->image));
            }

            $path = $request->file('image')->store('menus', 'public');
            $updateData['image'] = 'storage/' . $path;
        }

        $menu->update($updateData);

        return redirect()->route('merchant.home')->with('success', 'Menu Berhasil Diperbarui!');
    }
    /**
     * Fitur Ubah Status Borongan Cepet (Available <-> Empty) via Klik Tombol di Web
     */
    public function toggleStatus($id)
    {
        $menu = Menu::findOrFail($id);
        
        // Logika bolak-balik status
        $menu->status = ($menu->status === 'available') ? 'empty' : 'available';
        $menu->save();

        return redirect()->route('merchant.home')->with('success', 'Status menu ' . $menu->name . ' berhasil diperbarui!');
    }

  public function toggleStandStatus() {
    $user = Auth::user();
    
    // Ambil data stand milik merchant yang login
    $stand = DB::table('stands')->where('user_id', $user->id)->first();

    if (!$stand) {
        return redirect()->back()->with('error', 'Data stand tidak ditemukan.');
    }

    // Tentukan status barunya (Kalau open jadi close, kalau close jadi open)
    $statusBaru = ($stand->status === 'open') ? 'close' : 'open';

    // Update statusnya ke kolom 'status' yang baru kita buat di DBeaver tadi
    DB::table('stands')->where('id', $stand->id)->update([
        'status' => $statusBaru,
        'updated_at' => now()
    ]);

    $pesan = ($statusBaru === 'open') ? 'Stand berhasil DIBUKA!' : 'Stand berhasil DITUTUP!';
    return redirect()->back()->with('success', $pesan);
}

    /**
     * Pedagang menghapus menu dagangan miliknya lewat Web
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $stand = Stand::where('user_id', $user->id)->first();
        $menu = Menu::findOrFail($id);

        // Proteksi keamanan silang antar-stand kantin
        if ($menu->stand_id !== $stand->id) {
            return redirect()->route('merchant.home')->with('error', 'Ini bukan menu milik Anda!');
        }

        // Hapus file gambar fisik di lokal storage sebelum baris MySQL dihapus
        if ($menu->image && !str_contains($menu->image, 'http')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $menu->image));
        }

        $menu->delete();

        return redirect()->route('merchant.home')->with('success', 'Menu jualan berhasil dihapus secara permanen.');
    }

    public function listMenusMerchant() {
    $user = Auth::user();
    
    // Ambil data stand milik user yang login
    $stand = DB::table('stands')->where('user_id', $user->id)->first();

    // 🟢 AMANKAN DI SINI: Jika data stand tidak ditemukan di database
    if (!$stand) {
        // Buat koleksi menu kosong biar file Blade tidak eror saat looping foreach
        $menus = collect([]); 
        
        // Kembalikan ke halaman view sambil membawa info stand kosongan
        return view('merchant.menu', [
            'stand' => (object)[
                'stand_name' => 'Stand Belum Terdaftar', 
                'stand_number' => '-'
            ],
            'menus' => $menus
        ])->with('error', 'Akun Anda belum terhubung dengan stand kuliner manapun.');
    }

    // Jika stand ada, ambil data menu seperti biasa
    $menus = DB::table('menus')->where('stand_id', $stand->id)->get();

    return view('merchant.menu', compact('stand', 'menus'));
}
}