<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.surat-masuk.view');
    }

    public function create()
    {
        return view('admin.dashboard.surat-masuk-tambah');
    }
    public function edit()
    {
        return view('admin.dashboard.surat-masuk-edit');
    }
    public function show()
    {
        return view('admin.dashboard.surat-masuk-detail');
    }

}
