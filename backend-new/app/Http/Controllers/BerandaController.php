<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BerandaController extends Controller
{
    public function index()
    {
        $user = Session::get('user');

        $namaDepan = 'Sobat Kantin';
        if ($user && !empty($user['name'])) {
            $namaDepan = explode(' ', trim($user['name']))[0];
        }

        $foto = $user['photo_profile'] ?? null;

        return view('pelanggan.beranda', compact('namaDepan', 'foto'));
    }
}
