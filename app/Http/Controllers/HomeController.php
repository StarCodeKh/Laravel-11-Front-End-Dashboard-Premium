<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** home page dashboard */
    public function index(Request $request)
    {
        if ($request->session()->has('token')) {
            return view('dashboard.home'); // Redirect to the home page
        } else {
            flash()->error('You are not logged in.'); // Flash an error message if the session is not active
            return redirect()->route('login'); // Redirect to the login page if the session is not active
        }
    }
}
