<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersAdminController extends Controller
{
    public function index()
    {
        $users = [
            [
                'username' => 'admin',
                'name' => 'admin',
                'email' => 'adminLOOKSEE@gmail.com',
                'profile_picture' => '',
                'password' => '$2y$10$TDCTQMHinj3SHxA...',
                'role' => 'admin'
            ],
            [
                'username' => 'luuccy_',
                'name' => 'Lucy Maudy',
                'email' => 'lucyy@gmail.com',
                'profile_picture' => 'profile_15_1750001696.jpeg',
                'password' => '$2y$10$2.KvffaxJeu28iz2oEqdKlein...',
                'role' => 'konsumen'
            ],
            [
                'username' => 'luis',
                'name' => 'Luis Marcel',
                'email' => 'luis@gmail.com',
                'profile_picture' => 'profile_22_174986758.jpeg',
                'password' => '$2y$10$38wPZgOFF3FWKiOQL5QJC...',
                'role' => 'konsumen'
            ],
            [
                'username' => 'livviy',
                'name' => 'Livviy Tanaka', // Di screenshot baru 'Livviy Tanaka'
                'email' => 'livviyy@gmail.com',
                'profile_picture' => 'profile_24_1750036455.jpeg',
                'password' => '$2y$10$MPQmF1eYIHG2mgcJWc.U...',
                'role' => 'konsumen'
            ],
            [
                'username' => 'dior',
                'name' => 'Dior Majorie',
                'email' => 'dior@gmail.com',
                'profile_picture' => 'profile_25_1750054127.jpeg',
                'password' => '$2y$10$DCLRolu5F1uAP2u.37hhuiro...',
                'role' => 'konsumen'
            ],
            [
                'username' => 'whoolyy',
                'name' => 'Whooly Clar', 
                'email' => 'lyy@gmail.com',
                'profile_picture' => 'profile_26_1750053210.jpeg',
                'password' => '$2y$10$DLS46zqztKABc3M7RRO...',
                'role' => 'konsumen'
            ],
            [
                'username' => 'carlotee_',
                'name' => 'Carlote Ceyy',
                'email' => 'carrlo@gmail.com',
                'profile_picture' => 'profile_27_1750054292.jpeg',
                'password' => '$2y$10$rSetpmex95g8Vmm9AZs8...',
                'role' => 'konsumen'
            ],
            
            [
                'username' => 'syeca.my',
                'name' => 'Syeca Will',
                'email' => 'syey@gmail.com',
                'profile_picture' => 'profile_28_xxxx.jpeg',
                'password' => '$2y$10$rSetpmex95g8Vmm9AZs8...',
                'role' => 'konsumen'
            ],
            [
                'username' => 'griffinL',
                'name' => 'Gerry Levin',
                'email' => 'griff@gmail.com',
                'profile_picture' => 'profile_29_xxxx.jpeg',
                'password' => '$2y$10$rSetpmex95g8Vmm9AZs8...',
                'role' => 'konsumen'
            ],
            [
                'username' => 'lucas.julian',
                'name' => 'Lucas Julian',
                'email' => 'lcss@gmail.com',
                'profile_picture' => 'profile_30_xxxx.jpeg',
                'password' => '$2y$10$rSetpmex95g8Vmm9AZs8...',
                'role' => 'konsumen'
            ],
            [
                'username' => 'veliya',
                'name' => 'Veliya Renata',
                'email' => 'vel@gmail.com',
                'profile_picture' => 'profile_31_xxxx.jpeg',
                'password' => '$2y$10$rSetpmex95g8Vmm9AZs8...',
                'role' => 'konsumen'
            ],
        ];

        return view('userAdmin.usersAdmin', compact('users'));
    }
}