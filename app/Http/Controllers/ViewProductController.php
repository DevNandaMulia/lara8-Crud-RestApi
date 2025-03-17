<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewProductController extends Controller
{
    public function index()
    {
        return view('products.index'); // Hanya menampilkan view tanpa query database
    }
}
