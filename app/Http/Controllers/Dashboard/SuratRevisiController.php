<?php

// namespace App\Http\Controllers\Dashboard;

// use App\Http\Controllers\Controller;
// use App\Models\SuratRevisi;
// use Illuminate\Http\Request;

// class SuratRevisiController extends Controller
// {
//     public function index()
//     {

//         $revisiList = [
//             (object) [
//                 'id' => 1,
//                 'nomor_surat' => 'SK-001/KD/X/2025',
//                 'status' => 'ditolak',
//                 'komentar_revisi' => 'Tolong perbaiki paragraf kedua, terlalu panjang dan tidak jelas.',
//                 'file_revisi' => 'revisi/surat-keluar-1-revisi.docx',
//                 'judul_surat' => 'Undangan Rapat Koordinasi',
//                 'tanggal' => '2025-05-10',
//             ],
//             (object) [
//                 'id' => 2,
//                 'nomor_surat' => 'SK-002/KD/X/2025',
//                 'status' => 'diperbaiki',
//                 'komentar_revisi' => 'Sudah saya revisi, silakan periksa ulang.',
//                 'file_revisi' => null,
//                 'judul_surat' => 'Pemberitahuan Libur Nasional',
//                 'tanggal' => '2025-05-14',
//             ],
//         ];

//         return view('admin.dashboard.surat-revisi.view', compact('revisiList'));
//     }

//     public function create()
//     {
//         return view('admin.dashboard.surat-revisi.create');
//     }

//     public function show($id)
//     {
//         return view('admin.dashboard.surat-revisi.show', compact('id'));
//     }

//     public function update(Request $request, $id)
//     {
//         // Validasi input
//         $request->validate([
//             'komentar_revisi' => 'required|string',
//             'file_revisi' => 'nullable|file|mimes:docx,pdf|max:2048',
//         ]);

//         // Ambil data revisi yang akan diperbarui
//         $suratRevisi = \App\Models\SuratRevisi::findOrFail($id);
//         $suratRevisi->komentar_revisi = $request->komentar_revisi;

//         // Jika ada file revisi yang diunggah
//         if ($request->hasFile('file_revisi')) {
//             $file = $request->file('file_revisi');
//             $path = $file->store('revisi', 'public');
//             $suratRevisi->file_revisi = $path;
//         }

//         $suratRevisi->save();

//         // Setelah revisi disimpan, update juga data surat_keluar terkait
//         $suratKeluar = \App\Models\SuratKeluar::findOrFail($suratRevisi->surat_id);

//         // Misalnya kita update kolom status dan dokumen revisi
//         $suratKeluar->status_revisi = 'selesai'; // Atau 'direvisi'
//         if (isset($path)) {
//             $suratKeluar->dokumen_revisi_path = $path;
//         }

//         $suratKeluar->save();

//         return redirect()->route('surat-revisi')->with('success', 'Revisi surat berhasil diperbarui dan data surat keluar telah diupdate.');
//     }

//     public function edit($id)
//     {
//         $revisi = SuratRevisi::findOrFail($id);
//         return view('admin.dashboard.surat-revisi.edit', compact('revisi'));
//     }

// }




namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SuratRevisi;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;

class SuratRevisiController extends Controller
{
    public function index()
    {
        $revisiList = SuratRevisi::with('surat')->latest()->get();
        return view('admin.dashboard.surat-revisi.view', compact('revisiList'));
    }

    public function create()
    {
        return view('admin.dashboard.surat-revisi.create');
    }

    public function show($id)
    {
        $revisi = SuratRevisi::with('surat')->findOrFail($id);
        return view('admin.dashboard.surat-revisi.show', compact('revisi'));
    }

    public function edit($id)
    {
        $revisi = SuratRevisi::with('surat')->findOrFail($id);
        return view('admin.dashboard.surat-revisi.edit', compact('revisi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_surat' => 'required|string',
            'perihal' => 'required|string',
            'tujuan' => 'required|string',
            'isi' => 'required|string',
        ]);

        $suratRevisi = SuratRevisi::findOrFail($id);
        $surat = SuratKeluar::findOrFail($suratRevisi->surat_id);

        $surat->update([
            'nomor_surat' => $request->nomor_surat,
            'perihal' => $request->perihal,
            'tujuan' => $request->tujuan,
            'isi' => $request->isi,
            'status' => 'diajukan',
        ]);

        $suratRevisi->delete();

        return redirect()->route('surat-revisi')->with('success', 'Surat berhasil direvisi dan diajukan ulang.');
    }
}
