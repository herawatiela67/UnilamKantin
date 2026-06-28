<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Stand;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // 🟢 KODE YANG BENAR: Langsung panggil model User tanpa eager loading 'user'
        $users = User::where('id', '!=', auth()->id())->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:mahasiswa,merchant', 
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Data akun berhasil diperbarui!');
    }

    // 3. DELETE: Menghapus Akun
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus!');
    }
}