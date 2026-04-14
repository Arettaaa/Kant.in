<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CanteenController extends Controller
{
    public function index()
    {
        return view('admin_global.kantin');
    }

    public function filter()
    {
        return view('admin_global.filter-kantin');
    }
}
