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

    public function create(Request $request)
    {
        // SYMBOL/UBL/UrutanSuratTahunIni/BulanRilis/tahunRilis

        $request->validate([
            // 'nomor_surat' => 'required',
            'jenis_surat' => 'required',
            'perihal' => 'required',
            'tanggal' => 'required|date',
            'lampiran' => 'required',
            'isi_surat' => 'required',
        ]);

        $tahunRilisSurat = date('y', strtotime($request->tanggal));

        $bulanRilisSurat = date('m', strtotime($request->tanggal));


        $formatedSurat = $request->jenis_surat . '/' . 'UBL' . '/' . '010' . '/' . 'UrutanSuratTahunIni' . '/' . $bulanRilisSurat . '/' . $tahunRilisSurat;

        dd($formatedSurat);

        return view('admin.dashboard.surat-keluar.create');
    }

    public function show($id)
    {
        return view('admin.dashboard.surat-keluar.show', compact('id'));
    }
}
