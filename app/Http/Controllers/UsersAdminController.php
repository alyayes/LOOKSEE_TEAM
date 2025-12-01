<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // pastikan model User sudah ada

class UsersAdminController extends Controller
{
    public function index()
    {
        // Ambil semua user dari database
        $users = User::all(); // ambil semua kolom
        // Jika mau ambil kolom tertentu saja:
        // $users = User::select('username', 'name', 'email', 'profile_picture', 'role')->get();

        return view('admin.userAdmin.usersAdmin', compact('users'));
    }
}
