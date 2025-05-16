@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Surat Keluar</h2>
        <a href="#form-tambah" class="btn btn-primary">+ Tambah Surat Keluar</a>
    </div>

    <!-- Daftar Surat Keluar -->
    <div class="card mb-5">
        <div class="card-header">
            <strong>Daftar Surat Keluar</strong>
        </div>
        <div class="card-body">
            <!-- Simulasi tabel -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SK-001/KD/X/2025</td>
                        <td>16 Mei 2025</td>
                        <td><span class="badge bg-warning">Menunggu Persetujuan</span></td>
                        <td>
                            <button class="btn btn-sm btn-info">Lihat</button>
                            <button class="btn btn-sm btn-danger">Hapus</button>
                            <button class="btn btn-sm btn-warning">Review</button>
                        </td>
                    </tr>
                    <!-- Tambahkan data lainnya -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Tambah Surat Keluar -->
    <div class="card" id="form-tambah">
        <div class="card-header">
            <strong>Tambah Surat Keluar</strong>
        </div>
        <div class="card-body">
            <form action="/surat-keluar/store" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="template" class="form-label">Pilih Template Surat</label>
                    <select id="template" name="template" class="form-select" required>
                        <option value="">-- Pilih Template --</option>
                        <option value="undangan">Undangan</option>
                        <option value="pemberitahuan">Pemberitahuan</option>
                        <option value="surat-tugas">Surat Tugas</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Surat (otomatis)</label>
                    <input type="text" name="nomor_surat" class="form-control" value="SK-002/KD/X/2025" readonly>
                </div>

                <div class="mb-3">
                    <label for="lampiran" class="form-label">Lampiran (jumlah halaman)</label>
                    <input type="number" name="lampiran" id="lampiran" class="form-control" min="0" required>
                </div>

                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Surat</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Surat</label>
                    <textarea name="isi" id="isi" rows="5" class="form-control"
                        placeholder="Isi surat akan menyesuaikan template..." required></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="aksi" value="minta_persetujuan" class="btn btn-warning">
                        Minta Persetujuan
                    </button>
                    <button type="submit" name="aksi" value="langsung_cetak" class="btn btn-success">
                        Langsung Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
