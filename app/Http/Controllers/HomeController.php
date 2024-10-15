<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** home page dashboard */
    public function index()
    {
        return view('dashboard.home');
    }
}
