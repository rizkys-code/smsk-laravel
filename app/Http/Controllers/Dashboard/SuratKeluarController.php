<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\KomentarRevisi;
use App\Models\LampiranSurat;
use App\Models\SuratKeluar;
use App\Models\SuratRevisi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratKeluarController extends Controller
{
    public function index()
    {

        if (!Auth::check()) {
            return redirect()->route('login')->with('loginError', 'Login Required!');
        }

        // $dataSurat = SuratKeluar::with(['lampiran', 'pembuat', 'penyetuju'])->latest()->get();
        $dataSurat = SuratKeluar::orderBy('created_at', 'desc')->paginate(10);


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

        if ($request->aksi === 'langsung_cetak') {
            return redirect()->route('surat-keluar.print', $surat->id);
        }

        return redirect()->route('surat-keluar')->with('success', 'Surat berhasil disimpan dan menunggu persetujuan.');
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
        $lampiran = LampiranSurat::where('surat_id', $surat->id)->get();

        return view('admin.dashboard.surat-keluar.review', [
            'surat' => $surat,
            'lampiran' => $lampiran,
        ]);
    }

    public function cetak($id)
    {
        $surat = SuratKeluar::with('lampiran')->findOrFail($id);

        // if ($surat->status !== 'disetujui') {
        //     return redirect()->route('surat-keluar')->with('error', 'Surat belum disetujui, tidak bisa dicetak.');
        // }

        $safeNomorSurat = str_replace(['/', '\\'], '-', $surat->nomor_surat);

        $pdf = PDF::loadView('admin.dashboard.surat-keluar.print', compact('surat'));
        return $pdf->download('Surat-Keluar-' . $safeNomorSurat . '.pdf');

        // $pdf = PDF::loadView('admin.dashboard.surat-keluar.print', compact('surat'));
        // return $pdf->download('Surat-Keluar-' . $surat->nomor_surat . '.pdf');
    }
    // public function approval(Request $request, $id)
    // {
    //     $surat = SuratKeluar::findOrFail($id);

    //     $request->validate([
    //         'status' => 'required|in:disetujui,ditolak',
    //         'komentar_revisi' => 'nullable|string',
    //         'dokumen_revisi' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
    //     ]);

    //     $surat->status = $request->input('status');
    //     $surat->save();

    //     // Jika surat ditolak dan ada komentar revisi
    //     if ($request->status === 'ditolak' && $request->filled('komentar_revisi')) {
    //         $komentar = new KomentarRevisi();
    //         $komentar->surat_id = $surat->id;
    //         $komentar->komentar = $request->input('komentar_revisi');
    //         $komentar->created_by = auth()->id();

    //         // Jika ada file dokumen revisi, simpan dan set path
    //         if ($request->hasFile('dokumen_revisi')) {
    //             $path = $request->file('dokumen_revisi')->store('dokumen_revisi', 'public');
    //             $komentar->dokumen_revisi_path = $path;
    //         }

    //         $komentar->save();
    //     }

    //     return redirect()->route('surat-keluar.review', $id)
    //         ->with('success', 'Status surat berhasil diperbarui.');
    // }

    public function approval(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'komentar_revisi' => 'nullable|string',
        ]);

        $surat->status = $request->input('status');
        $surat->save();

        if ($request->status === 'ditolak' && $request->filled('komentar_revisi')) {
            SuratRevisi::create([
                'surat_id' => $surat->id,
                'komentar_revisi' => $request->komentar_revisi,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('surat-keluar.review', $id)
            ->with('success', 'Status surat berhasil diperbarui.');
    }

    public function tambahKomentar(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string',
            'dokumen_revisi' => 'nullable|file|mimes:pdf,doc,docx'
        ]);

        $path = null;
        if ($request->hasFile('dokumen_revisi')) {
            $path = $request->file('dokumen_revisi')->store('revisi_dokumen');
        }

        KomentarRevisi::create([
            'surat_id' => $id,
            'komentar' => $request->komentar,
            'created_by' => auth()->id(),
            'dokumen_revisi_path' => $path,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
