<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Menu;
use App\Models\Stand;

class AuthController extends Controller
{

public function showAdminLoginForm()
{
    return view('auth.admin-login'); // Kita akan buat file view ini
}

/**
 * Memproses Data Login Admin
 */
public function adminLogin(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Cek apakah benar dia admin sebelum dikasih masuk
        if (Gate::allows('access-admin') || Auth::user()->role === 'admin') {
      return redirect()->route('admin.stands.index');
        }

        // Kalau bukan admin tapi maksa login disini, usir logout!
        Auth::logout();
        return back()->withErrors(['email' => 'Anda tidak memiliki akses sebagai Administrator.']);
    }

    return back()->withErrors(['email' => 'Email atau password Admin salah.']);
}
    // 1. Tampilkan Halaman Login Blade yang Kemarin
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('student.home');
        }
        return view('auth.login');
    }

    public function adminLogout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin.login')->with('success', 'Berhasil keluar dari sistem Admin.');
}

    // 2. Eksekusi Proses Login Tanpa Token (Menggunakan Session)
  public function login(Request $request)
{
    // 1. Validasi input form login web
    $request->validate([
        'login_identifier' => 'required', // Menampung email atau NIM
        'password' => 'required',
    ]);

    $identifier = $request->input('login_identifier');
    $password = $request->input('password');

    // Deteksi inputan (murni 'email' sesuai kolom gabungan DB kamu)
    $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'email'; 

    // 2. Lakukan proses Auth Session
    if (Auth::attempt([$fieldType => $identifier, 'password' => $password])) {
        
        $user = Auth::user();

        // ======================================================================
        // 🟢 KUNCI PINTU: Hanya Izinkan User dengan Role 'mahasiswa'
        // ======================================================================
        if (strtolower($user->role) === 'mahasiswa') {
            $request->session()->regenerate();
            return redirect()->route('student.home')->with('success', 'Selamat datang kembali, ' . $user->name);
        }

        // ======================================================================
        // 🔴 BLOKIR TOTAL: Jika Pedagang/Merchant Nekat Login Lewat Jalur Mahasiswa
        // ======================================================================
        if (strtolower($user->role) === 'pedagang' || strtolower($user->role) === 'merchant') {
            Auth::logout(); // Tendang keluar detik itu juga
            
            return redirect()->back()
                ->withInput($request->only('login_identifier'))
                ->with('error', 'Akses Ditolak! Akun Anda terdaftar sebagai Pemilik Stand. Silakan gunakan halaman login khusus Mitra Stand yang telah disediakan.');
        }

        // Jika ada role asing lainnya yang mencoba masuk
        Auth::logout();
        return redirect()->back()
            ->withInput($request->only('login_identifier'))
            ->with('error', 'Role akun Anda tidak diizinkan mengakses halaman ini.');
    }

    // Jika password atau email/NIM memang salah dari awal
    return redirect()->back()
        ->withInput($request->only('login_identifier'))
        ->with('error', 'Email/NIM atau password salah, silahkan cek kembali');
}

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('student.home');
        }
        return view('auth.register'); // Menunjuk ke folder resources/views/auth/register.blade.php
    }

    // 2. Fungsi memproses data pendaftaran mahasiswa baru
   public function register(Request $request)
{
    // 1. Validasi input (tanpa perlu input role dari form)
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed', 
    ]);

    // 2. Simpan ke DB & Set ROLE otomatis di sini
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'mahasiswa', // 🟢 OTOMATIS DIKUNCI JADI MAHASISWA DI SINI
    ]);

    return redirect()->route('login')->with('success', 'Registrasi Mahasiswa Berhasil!');
}

    // 1. Tampilkan Halaman Login Khusus Merchant
public function showMerchantLoginForm()
{
    if (Auth::check()) {
        $user = Auth::user();
        if (strtolower($user->role) === 'pedagang' || strtolower($user->role) === 'merchant') {
            return redirect()->route('merchant.home');
        }
        return redirect()->route('student.home');
    }
    return view('auth.login_merchant'); // Menunjuk ke resources/views/auth/login_merchant.blade.php
}

// 2. Eksekusi Login Khusus Merchant (Validasi Ketat Satpam)
public function merchantLogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();

        // 👮‍♂️ Cek apakah dia beneran pedagang dan terdaftar stand-nya
        if (strtolower($user->role) === 'merchant' || strtolower($user->role) === 'merchant') {
            $standTerdaftar = \App\Models\Stand::where('user_id', $user->id)->exists();

            if ($standTerdaftar) {
                return redirect()->route('merchant.home')->with('success', 'Selamat datang di Dashboard, ' . $user->name);
            }

            Auth::logout();
            return redirect()->back()->withInput()->with('error', 'Akun Anda belum didaftarkan ke stand manapun oleh Admin!');
        }

        // Kalau mahasiswa nyasar login di sini, kita tolak!
        Auth::logout();
        return redirect()->back()->withInput()->with('error', 'Akses Ditolak! Halaman ini khusus Pemilik Stand Kantin.');
    }

    return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
}

    public function merchantHome()
    {
        $user = Auth::user();
        
        // Cari data stan yang dimiliki oleh user pedagang yang sedang login ini
        // Asumsi: di tabel 'stands' ada kolom 'user_id' yang tersambung ke id pengguna
        $stand = Stand::where('user_id', $user->id)->first();

        // Ambil semua menu yang terdaftar di stan tersebut
        $menus = $stand ? Menu::where('stand_id', $stand->id)->get() : collect();

        return view('merchant.home', compact('stand', 'menus'));
    }

    // 2. Menampilkan Form Tambah Menu
    public function createMenu()
    {
        return view('merchant.create_menu');
    }

// 3. Eksekusi Menyimpan Menu Baru ke Database
    public function storeMenu(Request $request)
    {
        $user = Auth::user();
        $stand = Stand::where('user_id', $user->id)->first();

        if (!$stand) {
            return redirect()->back()->with('error', 'Data stand kamu belum terdaftar di sistem!');
        }

        // Validasi inputan form tambah menu
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:100',
            'description' => 'nullable|string',
        ]);

        // Simpan data langsung ke tabel menus di DBeaver
        Menu::create([
            'stand_id' => $stand->id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'status' => 'ready', // Default status menu langsung siap dibeli
        ]);

        return redirect()->route('merchant.home')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    // 3. Proses Logout versi Web Monolith
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}