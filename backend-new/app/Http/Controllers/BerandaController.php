<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function index()
    {
        // Cek apakah ada user yang login
        if (Auth::check()) {
            $user = Auth::user();
            $namaDepan = explode(' ', $user->name)[0];
        } else {
            $namaDepan = "Sobat Kant.in";
        }
        
        return view('pelanggan.beranda', [
            'namaDepan' => $namaDepan
        ]);
    }
}