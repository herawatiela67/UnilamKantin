<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stand;
Use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class StandManagerController extends Controller
{
  public function index()
    {
        // Pakai with('user') agar teks "Akun Belum Diikat" hilang dan berganti nama pedagang asli!
        $stands = Stand::with('user')->latest()->get();
        // Ambil data user yang rolenya 'merchant' untuk pilihan dropdown saat edit nanti
        $merchants = User::where('role', 'merchant')->get();

        return view('admin.stand.index', compact('stands', 'merchants'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stand_name'   => 'required|string|max:255',
            'stand_number' => 'required|string|max:50',
            'user_id'      => 'required|exists:users,id',
            'category'     => 'required|string',
            'status'       => 'required|in:1,0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $stand = Stand::findOrFail($id);
        $stand->update([
            'stand_name'   => $request->stand_name,
            'stand_number' => $request->stand_number,
            'user_id'      => $request->user_id,
            'category'     => $request->category,
            'status'       => $request->status,
        ]);

       if ($request->hasFile('image')) {
        
        // Hapus foto banner lama dari folder storage jika sebelumnya sudah ada foto
        if ($stand->image && Storage::disk('public')->exists($stand->image)) {
            Storage::disk('public')->delete($stand->image);
        }

        // Simpan foto banner baru ke dalam folder 'public/stan-banners'
        $path = $request->file('image')->store('stan-banners', 'public');
        
        // Masukkan path foto baru ke dalam array data yang akan di-update
        $data['image'] = $path;
    }

    // 3. Update data stan beserta foto barunya (jika ada) ke database
    $stand->update($data);

    return redirect()->back()->with('success', 'Data stan kuliner berhasil diperbarui!');
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
            'user_id'      => $user->id, 
            'stand_name'   => $request->stand_name,
            'stand_number' => $request->stand_number,
            'description'  => $request->description,
            'category'     => $request->category,
            'image'        => $imagePath,
            'status'       => true,
        ]);

        return redirect()->route('admin.stands.index')->with('success', 'Pedagang baru dan Stan Kuliner berhasil didaftarkan secara bersamaan!');
    }

    public function destroy($id)
    {
        $stand = Stand::findOrFail($id);
        $stand->delete();

        return redirect()->back()->with('success', 'Stan kuliner berhasil dihapus dari sistem!');
    }
}