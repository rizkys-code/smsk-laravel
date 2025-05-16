<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LampiranSurat;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratKeluarController extends Controller
{
    public function index()
    {

        if (!Auth::check()) {
            return redirect()->route('login')->with('loginError', 'Login Required!');
        }


        $dataSurat = SuratKeluar::with(['lampiran', 'pembuat', 'penyetuju'])->latest()->get();



        return view('admin.dashboard.surat-keluar.view', compact('dataSurat'));
    }


    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'jenis_surat' => 'required',
            'perihal' => 'required',
            'tanggal' => 'required|date',
            'lampiran' => 'required|integer',
            'isi_surat' => 'required|string',
            // 'lampiran_data' => 'array',
        ]);


        $bulan = date('m', strtotime($request->tanggal));
        $tahun = date('y', strtotime($request->tanggal));

        $count = SuratKeluar::whereYear('tanggal', date('Y', strtotime($request->tanggal)))->count();
        $urutan = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $nomorSurat = "$request->jenis_surat/UBL/010/$urutan/$bulan/$tahun";

        // Simpan ke surat_keluar
        $surat = SuratKeluar::create([
            'nomor_surat' => $nomorSurat,
            'perihal' => $request->perihal,
            'isi' => $request->isi_surat,
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis_surat,
            'status' => $request->aksi === 'langsung_cetak' ? 'disetujui' : 'menunggu',
            'user_id' => auth()->id(),
        ]);

        // Simpan lampiran dinamis
        if ($request->has('lampiran')) {
            $lampiranArray = $request->lampiran;
            if (is_string($lampiranArray)) {
                $lampiranArray = json_decode($lampiranArray, true) ?? [];
            }
            if (is_array($lampiranArray)) {
                foreach ($lampiranArray as $index => $item) {
                    LampiranSurat::create([
                        'surat_id' => $surat->id,
                        'label' => $item['label'],
                        'isi' => $item['isi'],
                        'urutan_grup' => $index + 1,
                    ]);
                }
            }
        }

        return redirect()->route('surat-keluar')->with('success', 'Surat berhasil disimpan.');
    }


    public function show($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        return view('admin.dashboard.surat-keluar.detail', compact('surat'));
    }

    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $surat->delete();

        return redirect()->route('surat-keluar')->with('status', 'Surat berhasil dihapus.');
    }

    public function review($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        return view('admin.dashboard.surat-keluar.review', compact('surat'));
    }
}
