<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    /** Register page */
    public function register()
    {
        return view('authentication.register');
    }

    /** Register Account */
    public function registerAccount(Request $request)
    {
        $request->validate([
            'name'           => 'required|min:4',
            'email'          => 'required|string|email|max:255|unique:users',
            'password'       => 'required|min:8',
            'privacy_policy' => 'required',
        ]);
    
        try {
            
            $url = env('APP_URL') . '/api/register';
    
            // Make a POST request to the register API endpoint
            $response = Http::withOptions(['verify' => false])->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type'     => 'application/json',
            ])->post($url, [
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => $request->password,
            ]);
    
            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['errors'])) {
                // Handle the error
                if (isset($responseData['errors']['email'])) {
                    // Email already taken
                    flash()->error('The email has already been taken. Please use a different email.');
                } else {
                    flash()->error('Registration failed. Please try again.');
                }
                return redirect()->back();
               
            } else {
                flash()->success('Registration Successful :)');
                return redirect()->intended('login');
            }

        } catch (Exception $e) {
            // Handle the exception
            \Log::error('Registration error: ' . $e->getMessage());
            flash()->error('An error occurred during registration. Please try again later.');
            return redirect()->back();
        }
    }

    /** Login Page */
    public function login()
    {
        return view('authentication.login');
    }

    /** Login Account */
    public function loginAccount(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        
        try {
            $url  = env('APP_URL') . '/api/login'; 

            // Make a POST request to the login API endpoint
            $response = Http::withOptions(['verify' => false])->withHeaders([ 
                'X-Requested-With' => 'XMLHttpRequest', 
                'Content-Type'     => 'application/json', 
            ])->post($url, [ 
                'email'    => $request->email,
                'password' => $request->password,
            ]); 

            $responseData = json_decode($response->getBody(), true);
            if ($responseData['response_code'] == 200) {
                // Store the token in the session or a cookie
                $request->session()->put('token', $responseData['token']);
                flash()->success('Login Successfully :)');
                return redirect()->intended('home');
            } else {
                // Handle the error
                flash()->error('fail, WRONG USERNAME OR PASSWORD :)');
                return redirect()->back();
            }
        } catch (Exception $e) {
            // Handle the exception
            \Log::error('Login error: ' . $e->getMessage());
            flash()->error('An error occurred while logging in.');
            return redirect()->back();
        }
    }

    /** logOut Account */
    public function logoutAccount(Request $request)
    {
        try {
            $url = env('APP_URL') . '/api/logout'; // Correct endpoint for logout

            // Make a POST request to the logout API endpoint
            $response = Http::withOptions(['verify' => false])
            ->withHeaders([ 
                'X-Requested-With' => 'XMLHttpRequest', 
                'Authorization' => 'Bearer ' . Session::get('token'), // Include the token
            ])->post($url);
 
            if ($response->successful()) {
                // Clear the session token
                Session::forget('token');
                flash()->success('Logged out successfully!');
                return redirect()->route('login'); // Redirect to login or home
            } else {
                // Handle the error
                flash()->error('Logout failed, please try again.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // Handle the exception
            \Log::error('Logout error: ' . $e->getMessage());
            flash()->error('An error occurred while logging out.');
            return redirect()->back();
        }
    }
}