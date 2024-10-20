<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** home page dashboard */
    public function index(Request $request)
    {
        return view('dashboard.home'); // Redirect to the home page
    }
}
