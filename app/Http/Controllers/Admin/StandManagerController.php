<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stand;
Use App\Models\User;
use Illuminate\Support\Facades\Hash;


class StandManagerController extends Controller
{
   public function index()
{
    // Ambil semua data stan beserta relasi user biar tidak n+1 query
    $stands = \App\Models\Stand::all();

    // 🟢 Pastikan alamatnya tertulis 'admin.stands.index' (pakai titik sesuai subfolder)
    return view('admin.index', compact('stands'));
}

    // 2. Menampilkan halaman form tambah stan baru
    public function create()
    {
        // Karena input data baru langsung di form, kita tidak perlu ambil data merchants lagi
        return view('admin.create');
    }

    /**
     * PROSES ADMIN: Simpan User Pedagang Baru + Stan Barunya
     */
    public function store(Request $request)
    {
        // 1. Validasi inputan gabungan (User Akun + Detail Stan)
        $request->validate([
            // Validasi untuk Akun User Baru
            'owner_name'   => 'required|string|max:255',
            'owner_email'  => 'required|email|unique:users,email', // Email gak boleh kembar di DB
            'owner_password' => 'required|string|min:6',
            'stand_name'   => 'required|string|max:255',
            'stand_number' => 'required|string|max:50',
            'category'     => 'required|in:makanan,cemilan,minuman', 
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. KONTRAK UTAMA: Daftarkan dan simpan User Pedagang Baru dulu
        $user = User::create([
            'name'     => $request->owner_name,
            'email'    => $request->owner_email,
            'password' => Hash::make($request->owner_password), // Password di-enkripsi demi keamanan
            'role'  => 'merchant', // <-- Tambahkan ini jika tabel user kamu punya kolom role/level
        ]);

        // 3. Proses upload foto banner stan jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stands', 'public');
        }

        // 4. Hubungkan stan baru dengan ID user yang barusan kita buat ($user->id)
        Stand::create([
            'user_id'      => $user->id, // 🟢 Mengambil ID otomatis dari akun yang baru lahir di atas!
            'stand_name'   => $request->stand_name,
            'stand_number' => $request->stand_number,
            'description'  => $request->description,
            'category'     => $request->category,
            'image'        => $imagePath,
            'status'       => true,
        ]);

        return redirect()->route('admin.stands.index')->with('success', 'Pedagang baru dan Stan Kuliner berhasil didaftarkan secara bersamaan!');
    }
}