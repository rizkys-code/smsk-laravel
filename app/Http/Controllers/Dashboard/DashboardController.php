<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        if (!Auth::check()) {
            return redirect('/login')->with('loginError', 'Login Required!');
        }


        return view('admin.dashboard.dashboard');
    }


}
