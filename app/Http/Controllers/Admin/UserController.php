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
}