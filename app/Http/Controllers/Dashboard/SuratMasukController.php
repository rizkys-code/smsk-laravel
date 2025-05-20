<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Models\Disposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::orderBy('created_at', 'desc')->get();
        $suratMasuk = SuratMasuk::latest()->paginate(10);

        return view('admin.dashboard.surat-masuk.view', compact('suratMasuk'));
    }

    public function show($id)
    {
        $surat = SuratMasuk::findOrFail($id);

        return view('admin.dashboard.surat-masuk.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('admin.dashboard.surat-masuk.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'tanggal_surat' => 'required|date',
            'pengirim' => 'required|string',
            'instansi_pengirim' => 'required|string',
            'jabatan_pengirim' => 'required|string',
            'diketahui_oleh' => 'required|string',
            'jabatan_diketahui' => 'required|string',
            'perihal' => 'required|string',
            'jenis_surat' => 'required|string',
        ], [
            'instansi_pengirim.required' => 'Instansi pengirim harus diisi.',
            'instansi_pengirim.string' => 'Instansi pengirim harus berupa string.',
            'jabatan_pengirim.required' => 'Jabatan pengirim harus diisi.',
            'jabatan_pengirim.string' => 'Jabatan pengirim harus berupa string.',
            'diketahui_oleh.required' => 'Diketahui oleh harus diisi.',
            'diketahui_oleh.string' => 'Diketahui oleh harus berupa string.',
            'jabatan_diketahui.required' => 'Jabatan diketahui harus diisi.',
            'jabatan_diketahui.string' => 'Jabatan diketahui harus berupa string.',
            'perihal.required' => 'Perihal harus diisi.',
            'perihal.string' => 'Perihal harus berupa string.',
            'jenis_surat.required' => 'Jenis surat harus diisi.',
            'jenis_surat.string' => 'Jenis surat harus berupa string.',
            'tanggal_surat.required' => 'Tanggal surat harus diisi.',
            'tanggal_surat.date' => 'Tanggal surat harus berupa tanggal.',
            'pengirim.required' => 'Pengirim harus diisi.',
            'pengirim.string' => 'Pengirim harus berupa string.',
            'instansi_pengirim.required' => 'Instansi pengirim harus diisi.',
            'instansi_pengirim.string' => 'Instansi pengirim harus berupa string.',
            'jabatan_pengirim.required' => 'Jabatan pengirim harus diisi.',
            'jabatan_pengirim.string' => 'Jabatan pengirim harus berupa string.',
            'diketahui_oleh.required' => 'Diketahui oleh harus diisi.',
            'diketahui_oleh.string' => 'Diketahui oleh harus berupa string.',
            'jabatan_diketahui.required' => 'Jabatan diketahui harus diisi.',
            'jabatan_diketahui.string' => 'Jabatan diketahui harus berupa string.',

        ]);

        if ($request->hasFile('file')) {
            if ($suratMasuk->dokumen_surat) {
                Storage::disk('public')->delete($suratMasuk->dokumen_surat);
            }
            $validated['dokumen_surat'] = $request->file('file')->store('surat_masuk', 'public');
        }

        $suratMasuk->update($validated);
        return redirect()->route('surat-masuk')->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);

        if ($suratMasuk->dokumen_surat) {
            Storage::disk('public')->delete($suratMasuk->dokumen_surat);
        }

        $suratMasuk->delete();
        return redirect()->route('surat-masuk')->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function revision($id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            return redirect()->route('surat-masuk')->with('error', 'Anda tidak memiliki akses untuk melakukan revisi.');
        }

        $suratMasuk = SuratMasuk::findOrFail($id);
        return view('admin.dashboard.surat-masuk.revision', compact('suratMasuk'));
    }

    public function processRevision(Request $request, $id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            return redirect()->route('surat-masuk')->with('error', 'Anda tidak memiliki akses untuk melakukan revisi.');
        }

        $suratMasuk = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'catatan_revisi' => 'required|string',
            'status_revisi' => 'required|in:diterima,ditolak',
        ]);

        $suratMasuk->update([
            'catatan_revisi' => $validated['catatan_revisi'],
            'status_revisi' => $validated['status_revisi'],
            'tanggal_revisi' => now(),
        ]);

        return redirect()->route('surat-masuk')->with('success', 'Revisi surat berhasil diproses.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
            'tanggal_surat' => 'required|date',
            'pengirim' => 'required|string',
            'instansi' => 'required|string',
            'jabatan_pengirim' => 'required|string',
            'diketahui_oleh' => 'required|string',
            'jabatan_diketahui' => 'required|string',
            'perihal' => 'required|string',
            'jenis_surat' => 'required|string',
        ], [
            'file.required' => 'Dokumen surat harus diisi.',
            'file.file' => 'Dokumen surat harus berupa file.',
            'file.mimes' => 'Dokumen surat harus berupa file pdf, jpg, jpeg, png, doc, atau docx.',
            'file.max' => 'Dokumen surat maksimal 2MB.',
            'tanggal_surat.required' => 'Tanggal surat harus diisi.',
            'tanggal_surat.date' => 'Tanggal surat harus berupa tanggal.',
            'pengirim.required' => 'Pengirim harus diisi.',
            'jabatan_pengirim.required' => 'Jabatan pengirim harus diisi.',
            'diketahui_oleh.required' => 'Diketahui oleh harus diisi.',
            'jabatan_diketahui.required' => 'Jabatan diketahui harus diisi.',
            'perihal.required' => 'Perihal harus diisi.',
            'jenis_surat.required' => 'Jenis surat harus diisi.',
            'file.max' => 'Dokumen surat maksimal 2MB.',
            'tanggal_surat.date' => 'Tanggal surat harus berupa tanggal.',
            'pengirim.string' => 'Pengirim harus berupa string.',
            'jabatan_pengirim.string' => 'Jabatan pengirim harus berupa string.',
            'diketahui_oleh.string' => 'Diketahui oleh harus berupa string.',
            'jabatan_diketahui.string' => 'Jabatan diketahui harus berupa string.',
            'perihal.string' => 'Perihal harus berupa string.',
            'jenis_surat.string' => 'Jenis surat harus berupa string.',
            'instansi.required' => 'Instansi pengirim harus diisi.',
            'instansi.string' => 'Instansi pengirim harus berupa string.',
        ]);

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('surat_masuk', 'public');

            SuratMasuk::create([
                'dokumen_surat' => $filePath,
                'tanggal_surat' => $request->tanggal_surat,
                'pengirim' => $request->pengirim,
                'instansi_pengirim' => $request->instansi,
                'jabatan_pengirim' => $request->jabatan_pengirim,
                'diketahui_oleh' => $request->diketahui_oleh,
                'jabatan_diketahui' => $request->jabatan_diketahui,
                'perihal' => $request->perihal,
                'jenis_surat' => $request->jenis_surat,
            ]);

            return redirect()->route('surat-masuk')->with('success', 'Surat masuk berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    public function disposisi(Request $request, $id)
    {
        $request->validate([
            'tujuan_disposisi' => 'required|string',
            'catatan_disposisi' => 'nullable|string',
            'prioritas' => 'required|string',
            'tenggat_waktu' => 'nullable|date',
        ]);

        Disposisi::create([
            'surat_masuk_id' => $id,
            'tujuan' => $request->tujuan_disposisi,
            'catatan' => $request->catatan_disposisi,
            'prioritas' => $request->prioritas,
            'tenggat_waktu' => $request->tenggat_waktu,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('surat-masuk')->with('success', 'Disposisi berhasil dikirim.');
    }

    public function updateFile(Request $request, $id)
    {
        $request->validate([
            'new_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $surat = SuratMasuk::findOrFail($id);

        if ($surat->dokumen_surat && Storage::disk('public')->exists($surat->dokumen_surat)) {
            Storage::disk('public')->delete($surat->dokumen_surat);
        }

        $filePath = $request->file('new_file')->store('surat-masuk', 'public');

        $surat->dokumen_surat = $filePath;
        $surat->save();

        return redirect()->route('surat-masuk.show', $id)
            ->with('status', 'Dokumen surat berhasil diperbarui.');
    }

    public function review($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('admin.dashboard.surat-masuk.review', compact('surat'));
    }

    public function submitReview(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $surat->update([
            'status_review' => $request->status_review,
        ]);

        return redirect()->route('surat-masuk.show', $id)->with('status', 'Review surat berhasil disimpan.');
    }

}

