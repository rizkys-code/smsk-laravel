<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuratKeluarController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.surat-keluar.view');
    }

    public function create()
    {
        return view('admin.dashboard.surat-keluar.create');
    }

    public function show($id)
    {
        return view('admin.dashboard.surat-keluar.show', compact('id'));
    }
}
