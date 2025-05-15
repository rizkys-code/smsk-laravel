<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/admin', function(){
    return view('admin.login_admin');
}); 



Route::get('/welcome', function () {
    return view('welcome', ['title' => 'Home Page']);
}); 

Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/login', [LoginController::class, 'index']);

Route::get("/tester", [LoginController::class, "registrasi"]);