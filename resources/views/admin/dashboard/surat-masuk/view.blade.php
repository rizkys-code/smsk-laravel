@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Surat Masuk</h2>
    <a href="#form-tambah" class="btn btn-primary">+ Tambah Surat Masuk</a>
</div>

<!-- Daftar Surat Masuk -->
<div class="card mb-5">
    <div class="card-header">
        <strong>Daftar Surat Masuk</strong>
    </div>
    <div class="card-body">
        <!-- Simulasi tabel -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Surat</th>
                    <th>Jenis</th>
                    <th>Instansi</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Simulasi data -->
                <tr>
                    <td>Undangan-Kemendikbud-2025-05-16</td>
                    <td>Undangan</td>
                    <td>Kemendikbud</td>
                    <td>16 Mei 2025</td>
                    <td>
                        @if(Auth::user()->role === 'superadmin')
                            <button class="btn btn-sm btn-warning">Edit</button>
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        @else
                            <span class="text-muted">Hanya Superadmin</span>
                        @endif
                    </td>
                </tr>
                <!-- Ulangi untuk data lainnya -->
            </tbody>
        </table>
    </div>
</div>

<!-- Form Tambah Surat Masuk -->
<div class="card" id="form-tambah">
    <div class="card-header">
        <strong>Tambah Surat Masuk</strong>
    </div>
    <div class="card-body">
        <form action="/surat-masuk/store" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis Surat</label>
                <select id="jenis" name="jenis" class="form-select" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Undangan">Undangan</option>
                    <option value="Permohonan">Permohonan</option>
                    <option value="Pemberitahuan">Pemberitahuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="instansi" class="form-label">Nama Instansi / Perorangan</label>
                <input type="text" id="instansi" name="instansi" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="file" class="form-label">Upload Surat (PDF, Word, Scan)</label>
                <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg" required>
            </div>

            <input type="hidden" name="nama_surat" id="nama_surat">

            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
</div>

<!-- JS untuk auto-nama surat -->
<script>
    const jenisSelect = document.getElementById('jenis');
    const instansiInput = document.getElementById('instansi');
    const namaSuratInput = document.getElementById('nama_surat');

    function updateNamaSurat() {
        const jenis = jenisSelect.value;
        const instansi = instansiInput.value.replace(/\s+/g, '');
        const tgl = new Date().toISOString().split('T')[0];
        if (jenis && instansi) {
            namaSuratInput.value = `${jenis}-${instansi}-${tgl}`;
        }
    }

    jenisSelect.addEventListener('change', updateNamaSurat);
    instansiInput.addEventListener('input', updateNamaSurat);
</script>
@endsection
