<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login_admin', [
            'title' => 'Login'
        ]);
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/welcome');
        }   

        return back()->with("loginError", "Login Failed!");
    }

    public function registrasi(){
        $admin = "admin";
        $data = User::create([
            "username" => "superadmin",
            "password" => Hash::make("superadmin"),
            "name" => "kepala lab",
            "role" => "superadmin"
        ]);

        User::create([
            "username" => "admin",
            "password" => Hash::make($admin),
            "name" => "spv",
        ]);
        return $data;  


    }
}


