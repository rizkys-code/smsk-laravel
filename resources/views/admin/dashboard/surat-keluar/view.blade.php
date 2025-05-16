@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Surat Keluar</h2>
        <a href="#form-tambah" class="btn btn-primary">+ Tambah Surat Keluar</a>
    </div>

    <!-- Daftar Surat Keluar -->
    <div class="card mb-5">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="card-header">
            <strong>Daftar Surat Keluar</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Jenis Surat</th>
                        <th>No. Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Isi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataSurat as $surat)
                        @php

                            $kodeSurat = [
                                'SA' => 'Sertif Asisten / PKL',
                                'SS' => 'Sertif Webinar / Workshop / Media Partner',
                                'H' => 'Surat Perbaikan',
                                'P' => 'Formulir Pendaftaran Calas',
                                'S' => 'SK Asisten / Keterangan',
                                'U' => 'Surat Undangan',
                                'K' => 'Surat Keputusan',
                            ];

                            $jenis_surat =  $kodeSurat[$surat->jenis];

                        @endphp
                        <tr>
                            <td>{{ $jenis_surat }}</td>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>{{ $surat->tanggal }}</td>
                            <td><span class="badge bg-warning">{{ $surat->status }}</span></td>
                            <td>{{ $surat->isi }}</td>
                            <td>
                                <a href="{{ route('surat-keluar.show', $surat->id) }}" class="btn btn-sm btn-info">Lihat</a>

                                <form action="{{ route('surat-keluar.destroy', $surat->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>

                                <a href="{{ route('surat-keluar.review', $surat->id) }}"
                                    class="btn btn-sm btn-warning">Review</a>
                            </td>

                        </tr>
                    @endforeach

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
            <form action="{{ route('surat-keluar.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="jenis_surat" class="form-label">Pilih Jenis Surat</label>
                    <select id="jenis_surat" name="jenis_surat" class="form-select" required>
                        <option value="">-- Pilih Jenis Surat --</option>
                        <option value="SA">Sertif Asisten / PKL</option>
                        <option value="SS">Sertif Webinar / Workshop / Media Partner</option>
                        <option value="H">Surat Perbaikan</option>
                        <option value="P">Formulir Pendaftaran Calas</option>
                        <option value="S">SK Asisten / Keterangan</option>
                        <option value="U">Surat Undangan</option>
                        <option value="K">Surat Keputusan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="perihal" class="form-label">Perihal Surat</label>
                    <input type="text" name="perihal" id="perihal" class="form-control" placeholder="Perihal Surat"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Surat (otomatis)</label>
                    <input type="text" name="nomor_surat" class="form-control" value="" readonly>
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
                    <label for="isi_surat" class="form-label">Isi Surat</label>
                    <textarea name="isi_surat" id="isi_surat" rows="5" class="form-control"
                        placeholder="Isi surat akan menyesuaikan template..." required></textarea>
                </div>

                <!-- Lampiran Dinamis -->
                {{-- <div class="mb-3">
                    <label class="form-label">Lampiran Tambahan (Dinamis)</label>
                    <div id="lampiran-container">
                        <div class="row mb-2 lampiran-group">
                            <div class="col-md-5">
                                <input type="text" name="lampiran[0][label]" class="form-control"
                                    placeholder="Label (misal: Nama)" >
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="lampiran[0][isi]" class="form-control"
                                    placeholder="Isi (misal: John Doe)" >
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="tambahLampiran()">+ Tambah Baris
                        Lampiran</button>
                </div> --}}

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

    <script>
        let indexLampiran = 1;

        function tambahLampiran() {
            const container = document.getElementById('lampiran-container');
            const row = document.createElement('div');
            row.className = 'row mb-2 lampiran-group';
            row.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="lampiran[${indexLampiran}][label]" class="form-control" placeholder="Label" required>
            </div>
            <div class="col-md-6">
                <input type="text" name="lampiran[${indexLampiran}][isi]" class="form-control" placeholder="Isi" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="hapusLampiran(this)">Hapus</button>
            </div>
        `;
            container.appendChild(row);
            indexLampiran++;
        }

        function hapusLampiran(button) {
            const row = button.closest('.lampiran-group');
            row.remove();
        }
    </script>
@endsection
