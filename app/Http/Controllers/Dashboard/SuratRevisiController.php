<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuratRevisiController extends Controller
{
    public function index()
    {

        $revisiList = [
            (object) [
                'id' => 1,
                'nomor_surat' => 'SK-001/KD/X/2025',
                'status' => 'ditolak',
                'komentar_revisi' => 'Tolong perbaiki paragraf kedua, terlalu panjang dan tidak jelas.',
                'file_revisi' => 'revisi/surat-keluar-1-revisi.docx',
                'judul_surat' => 'Undangan Rapat Koordinasi',
                'tanggal' => '2025-05-10',
            ],
            (object) [
                'id' => 2,
                'nomor_surat' => 'SK-002/KD/X/2025',
                'status' => 'diperbaiki',
                'komentar_revisi' => 'Sudah saya revisi, silakan periksa ulang.',
                'file_revisi' => null,
                'judul_surat' => 'Pemberitahuan Libur Nasional',
                'tanggal' => '2025-05-14',
            ],
        ];

        return view('admin.dashboard.surat-revisi.view', compact('revisiList'));
    }

    public function create()
    {
        return view('admin.dashboard.surat-revisi.create');
    }

    public function show($id)
    {
        return view('admin.dashboard.surat-revisi.show', compact('id'));
    }
}
